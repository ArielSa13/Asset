<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Loan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

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
            'borrower_department' => 'required|string|max:255',
            'borrower_phone'      => 'nullable|string|max:20',
            'borrowed_at'         => 'required|date',
            'expected_return_at'  => 'nullable|date|after:borrowed_at',
            'condition_before'    => 'required|in:good,fair,poor,broken',
            'purpose'             => 'required|string|max:500',
            'notes'               => 'nullable|string',
        ]);

        $loan = Loan::create($validated);
        $loan->asset->update([
            'status'            => Asset::STATUS_IN_USE,
            'original_location' => $loan->asset->location,
            'location'          => 'Dipinjam oleh: ' . $loan->borrower_name,
        ]);

        return redirect()->route('loans.index')
            ->with('success', 'Peminjaman berhasil dibuat.');
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
        $pdf = Pdf::loadView('loans.pdf', compact('loan', 'now'));
        return $pdf->download("handover-{$loan->id}.pdf");
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
