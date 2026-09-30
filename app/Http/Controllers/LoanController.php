<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Loan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\LoanDocumentSequence;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = Loan::with('asset')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('borrower_name', 'like', "%{$request->search}%")
                    ->orWhere('borrower_department', 'like', "%{$request->search}%")
                    ->orWhereHas('asset', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
            });
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'active'   => $query->whereNull('returned_at')->where(fn($q) => $q->whereNull('expected_return_at')->orWhere('expected_return_at', '>=', now())),
                'overdue'  => $query->whereNull('returned_at')->where('expected_return_at', '<', now()),
                'returned' => $query->whereNotNull('returned_at'),
                default    => null,
            };
        }

        $loans = $query->paginate(15)->withQueryString();

        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $assets = Asset::where('status', Asset::STATUS_AVAILABLE)->orderBy('name')->get();
        return view('loans.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id'            => 'required|exists:assets,id',
            'borrower_name'       => 'required|string|max:255',
            'borrower_position'   => 'required|string|max:255',
            'borrower_department' => 'required|string|max:255',
            'borrower_phone'      => 'nullable|string|max:20',
            'borrowed_at'         => 'required|date',
            'expected_return_at'  => 'nullable|date|after:borrowed_at',
            'condition_before'    => 'required|in:good,fair,poor,broken',
            'purpose'             => 'required|string|max:500',
            'notes'               => 'nullable|string',
        ]);

        $loan = DB::transaction(function () use ($validated) {

            /*
         * Tanggal surat mengikuti tanggal peminjaman.
         */
            $borrowedAt = \Carbon\Carbon::parse($validated['borrowed_at'])
                ->setTimezone('Asia/Jakarta');

            $year = $borrowedAt->year;
            $month = $borrowedAt->month;

            /*
         * Ambil counter bulan tersebut.
         * Kalau belum ada, buat.
         */
            $sequence = LoanDocumentSequence::where('year', $year)
                ->where('month', $month)
                ->lockForUpdate()
                ->first();

            if (! $sequence) {
                $sequence = LoanDocumentSequence::create([
                    'year'        => $year,
                    'month'       => $month,
                    'last_number' => 0,
                ]);

                /*
             * Ambil kembali dengan lock untuk memastikan
             * transaksi berikutnya tidak menggunakan nomor sama.
             */
                $sequence = LoanDocumentSequence::where('id', $sequence->id)
                    ->lockForUpdate()
                    ->first();
            }

            $sequence->increment('last_number');

            $number = $sequence->last_number;

            /*
         * Bulan dalam angka Romawi.
         */
            $romanMonths = [
                1  => 'I',
                2  => 'II',
                3  => 'III',
                4  => 'IV',
                5  => 'V',
                6  => 'VI',
                7  => 'VII',
                8  => 'VIII',
                9  => 'IX',
                10 => 'X',
                11 => 'XI',
                12 => 'XII',
            ];

            $documentNumber = sprintf(
                'NO. %03d/VMB-HCGS/INT/%s/%d',
                $number,
                $romanMonths[$month],
                $year
            );

            /*
         * Simpan loan + nomor dokumen.
         */
            $loan = Loan::create(array_merge(
                $validated,
                [
                    'document_number' => $documentNumber,
                ]
            ));

            /*
         * Update status asset.
         */
            $loan->asset->update([
                'status'            => Asset::STATUS_IN_USE,
                'original_location' => $loan->asset->location,
                'location'          => 'Dipinjam oleh: ' . $loan->borrower_name,
            ]);

            return $loan;
        });

        return redirect()->route('loans.index')
            ->with('success', 'Peminjaman berhasil dibuat. Nomor berita acara: ' . $loan->document_number);
    }

    public function show(Loan $loan)
    {
        $loan->load('asset');
        return view('loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        if ($loan->isReturned()) {
            return redirect()->route('loans.index')->with('error', 'Peminjaman yang sudah dikembalikan tidak dapat diedit.');
        }
        $assets = Asset::where('status', Asset::STATUS_IN_USE)->orWhere('id', $loan->asset_id)->get();
        return view('loans.edit', compact('loan', 'assets'));
    }

    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'borrower_name'       => 'required|string|max:255',
            'borrower_position'   => 'required|string|max:255',
            'borrower_department' => 'required|string|max:255',
            'borrower_phone'      => 'nullable|string|max:20',
            'expected_return_at'  => 'nullable|date',
            'purpose'             => 'required|string|max:500',
            'notes'               => 'nullable|string',
        ]);

        $loan->update($validated);

        return redirect()->route('loans.index')
            ->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function returnAsset(Request $request, Loan $loan)
    {
        $request->validate([
            'condition_after' => 'required|in:good,fair,poor,broken',
            'return_location' => 'nullable|string|max:255',
            'notes'           => 'nullable|string',
        ]);

        $loan->update([
            'returned_at'     => now()->setTimezone('Asia/Jakarta'),
            'condition_after' => $request->condition_after,
            'notes'           => $request->notes,
        ]);

        $loan->asset->update([
            'condition' => $request->condition_after,
            'status'    => in_array($request->condition_after, ['broken', 'poor'])
                ? Asset::STATUS_MAINTENANCE
                : Asset::STATUS_AVAILABLE,
            'location'  => $request->return_location ?: $loan->asset->original_location,
        ]);

        return redirect()->route('loans.index')
            ->with('success', 'Asset berhasil dikembalikan.');
    }

    public function destroy(Loan $loan)
    {
        if (! $loan->isReturned()) {
            $loan->asset->update(['status' => Asset::STATUS_AVAILABLE]);
        }
        $loan->delete();

        return redirect()->route('loans.index')
            ->with('success', 'Data peminjaman dihapus.');
    }

    /**
     * Export handover document satu peminjaman ke PDF
     */
    public function exportPdf(Loan $loan)
    {
        $loan->load('asset');

        $now = now()->setTimezone('Asia/Jakarta');

        /*
     * Tanda tangan Penerima / Peminjam
     */
        $signaturePath = null;

        if ($loan->borrower_signature) {

            $path = Storage::disk('public')->path(
                $loan->borrower_signature
            );

            if (is_file($path)) {
                $signaturePath = $path;
            }
        }

        /*
     * Tanda tangan Pemberi / IT Support
     */
        $itSupportSignature = storage_path(
            'app/public/signatures/ariel.png'
        );

        if (! is_file($itSupportSignature)) {
            $itSupportSignature = null;
        }

        /*
     * Generate PDF
     */
        $pdf = Pdf::loadView(
            'loans.pdf',
            compact(
                'loan',
                'now',
                'signaturePath',
                'itSupportSignature'
            )
        );

        $filename = 'BAST-' .
            str_replace(
                ['NO. ', '/'],
                ['', '-'],
                $loan->document_number
            ) .
            '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export seluruh rekap peminjaman ke PDF
     */
    public function exportAllPdf(Request $request)
    {
        $query = Loan::with('asset')->latest();

        if ($request->filled('status')) {
            match ($request->status) {
                'active'   => $query->whereNull('returned_at'),
                'overdue'  => $query->whereNull('returned_at')->where('expected_return_at', '<', now()),
                'returned' => $query->whereNotNull('returned_at'),
                default    => null,
            };
        }

        $loans = $query->get();
        $now   = now()->setTimezone('Asia/Jakarta');

        $pdf = Pdf::loadView('loans.pdf-all', compact('loans', 'now'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('rekap-peminjaman-' . $now->format('Ymd') . '.pdf');
    }
}
