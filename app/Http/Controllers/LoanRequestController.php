<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Loan;
use App\Models\LoanRequest;
use App\Models\LoanDocumentSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LoanRequestController extends Controller
{
    /**
     * Form publik untuk karyawan — tanpa login
     */
    public function publicForm()
    {
        $assets = Asset::with('assetCategory')
            ->where('status', 'available')
            ->orderBy('name')
            ->get();

        return view('loan-requests.form', compact('assets'));
    }

    /**
     * Simpan request dari karyawan
     */
    public function publicStore(Request $request)
    {
        $validated = $request->validate([
            'asset_id'            => 'required|exists:assets,id',
            'borrower_name'       => 'required|string|max:255',
            'borrower_position'   => 'required|string|max:255',
            'borrower_department' => 'nullable|string|max:255',
            'borrower_phone'      => 'nullable|string|max:50',
            'borrower_signature'  => 'required|string',
            'purpose'             => 'required|string|max:500',
            'notes'               => 'nullable|string|max:1000',
        ], [
            'asset_id.required' =>
            'Pilih asset yang ingin dipinjam.',

            'borrower_name.required' =>
            'Nama peminjam wajib diisi.',

            'borrower_position.required' =>
            'Jabatan peminjam wajib diisi.',

            'borrower_signature.required' =>
            'Tanda tangan wajib diisi.',

            'purpose.required' =>
            'Keperluan peminjaman wajib diisi.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan asset masih tersedia
        |--------------------------------------------------------------------------
        */

        $asset = Asset::findOrFail($validated['asset_id']);

        if ($asset->status !== 'available') {
            return back()
                ->withErrors([
                    'asset_id' => 'Asset ini sudah tidak tersedia.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Cek pending request
        |--------------------------------------------------------------------------
        */

        $existingRequest = LoanRequest::where(
            'asset_id',
            $validated['asset_id']
        )
            ->where('status', 'pending')
            ->exists();

        if ($existingRequest) {
            return back()
                ->withErrors([
                    'asset_id' =>
                    'Asset ini sudah ada permintaan peminjaman yang sedang menunggu persetujuan.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan tanda tangan
        |--------------------------------------------------------------------------
        */

        $signaturePath = null;

        try {

            $signature = $validated['borrower_signature'];

            /*
             * Pastikan format data URI PNG
             */
            if (! preg_match(
                '/^data:image\/png;base64,/',
                $signature
            )) {
                return back()
                    ->withErrors([
                        'borrower_signature' =>
                        'Format tanda tangan tidak valid.',
                    ])
                    ->withInput();
            }

            /*
             * Ambil bagian base64
             */
            $signatureData = substr(
                $signature,
                strpos($signature, ',') + 1
            );

            $signatureData = base64_decode(
                $signatureData,
                true
            );

            if ($signatureData === false) {
                return back()
                    ->withErrors([
                        'borrower_signature' =>
                        'Data tanda tangan tidak valid.',
                    ])
                    ->withInput();
            }

            /*
             * Batasi ukuran signature 2 MB
             */
            if (strlen($signatureData) > 2 * 1024 * 1024) {
                return back()
                    ->withErrors([
                        'borrower_signature' =>
                        'Ukuran tanda tangan terlalu besar.',
                    ])
                    ->withInput();
            }

            /*
             * Pastikan benar-benar PNG
             */
            $imageInfo = getimagesizefromstring($signatureData);

            if (
                $imageInfo === false ||
                ($imageInfo['mime'] ?? null) !== 'image/png'
            ) {
                return back()
                    ->withErrors([
                        'borrower_signature' =>
                        'File tanda tangan harus berupa PNG.',
                    ])
                    ->withInput();
            }

            /*
             * Nama file random
             */
            $signaturePath =
                'signatures/' .
                \Illuminate\Support\Str::uuid() .
                '.png';

            Storage::disk('public')->put(
                $signaturePath,
                $signatureData
            );

            /*
            |--------------------------------------------------------------------------
            | Simpan request
            |--------------------------------------------------------------------------
            */

            LoanRequest::create([
                'asset_id'            => $validated['asset_id'],
                'borrower_name'       => $validated['borrower_name'],
                'borrower_position'   => $validated['borrower_position'],
                'borrower_department' => $validated['borrower_department'] ?? null,
                'borrower_phone'      => $validated['borrower_phone'] ?? null,
                'borrower_signature'  => $signaturePath,
                'purpose'             => $validated['purpose'],
                'notes'               => $validated['notes'] ?? null,
                'status'              => 'pending',
            ]);
        } catch (\Exception $e) {

            /*
             * Kalau gagal simpan request,
             * hapus signature yang sudah tersimpan.
             */
            if ($signaturePath) {
                Storage::disk('public')->delete($signaturePath);
            }

            return back()
                ->withErrors([
                    'borrower_signature' =>
                    'Gagal menyimpan tanda tangan.',
                ])
                ->withInput();
        }

        return redirect()
            ->route('loan-requests.public.success');
    }

    /**
     * Halaman sukses setelah submit
     */
    public function publicSuccess()
    {
        return view('loan-requests.success');
    }

    /**
     * List semua request — admin
     */
    public function index()
    {
        $requests = LoanRequest::with([
            'asset.assetCategory',
            'loan',
        ])
            ->latest()
            ->paginate(15);

        $pendingCount = LoanRequest::where(
            'status',
            'pending'
        )->count();

        return view(
            'loan-requests.index',
            compact(
                'requests',
                'pendingCount'
            )
        );
    }

    /**
     * Approve request → buat Loan + nomor BAST
     */
    public function approve(
        Request $request,
        LoanRequest $loanRequest
    ) {
        if ($loanRequest->status !== 'pending') {
            return back()->with(
                'error',
                'Request ini sudah diproses.'
            );
        }

        $request->validate([
            'expected_return_at' =>
            'nullable|date|after:today',
        ]);

        try {

            $loan = DB::transaction(function () use (
                $request,
                $loanRequest
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock request
                |--------------------------------------------------------------------------
                */

                $lockedRequest = LoanRequest::lockForUpdate()
                    ->findOrFail($loanRequest->id);

                if ($lockedRequest->status !== 'pending') {
                    throw new \Exception(
                        'Request ini sudah diproses.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Lock asset
                |--------------------------------------------------------------------------
                */

                $asset = Asset::lockForUpdate()
                    ->findOrFail(
                        $lockedRequest->asset_id
                    );

                if ($asset->status !== 'available') {
                    throw new \Exception(
                        'Asset sudah tidak tersedia.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Tentukan tahun dan bulan BAST
                |--------------------------------------------------------------------------
                */

                $borrowedAt = now()->setTimezone(
                    'Asia/Jakarta'
                );

                $year = (int) $borrowedAt->format('Y');
                $month = (int) $borrowedAt->format('n');

                /*
                |--------------------------------------------------------------------------
                | Ambil / buat sequence bulan ini
                |--------------------------------------------------------------------------
                */

                $sequence = LoanDocumentSequence::where(
                    'year',
                    $year
                )
                    ->where(
                        'month',
                        $month
                    )
                    ->lockForUpdate()
                    ->first();

                if (! $sequence) {

                    $sequence =
                        LoanDocumentSequence::create([
                            'year'        => $year,
                            'month'       => $month,
                            'last_number' => 0,
                        ]);

                    /*
                     * Lock row yang baru dibuat
                     */
                    $sequence->refresh();
                }

                /*
                |--------------------------------------------------------------------------
                | Nomor berikutnya
                |--------------------------------------------------------------------------
                */

                $nextNumber =
                    $sequence->last_number + 1;

                /*
                |--------------------------------------------------------------------------
                | Bulan → Romawi
                |--------------------------------------------------------------------------
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

                $romanMonth =
                    $romanMonths[$month];

                /*
                |--------------------------------------------------------------------------
                | Buat nomor BAST
                |--------------------------------------------------------------------------
                |
                | Contoh:
                | NO. 001/VMB-HCGS/INT/IX/2026
                |
                */

                $documentNumber = sprintf(
                    'NO. %03d/VMB-HCGS/INT/%s/%d',
                    $nextNumber,
                    $romanMonth,
                    $year
                );

                /*
                |--------------------------------------------------------------------------
                | Buat Loan
                |--------------------------------------------------------------------------
                */

                $loan = Loan::create([
                    'asset_id' =>
                    $lockedRequest->asset_id,

                    'borrower_name' =>
                    $lockedRequest->borrower_name,

                    'borrower_position' =>
                    $lockedRequest->borrower_position,

                    'borrower_department' =>
                    $lockedRequest->borrower_department,

                    'borrower_phone' =>
                    $lockedRequest->borrower_phone,

                    'borrower_signature' =>
                    $lockedRequest->borrower_signature,

                    'borrowed_at' =>
                    $borrowedAt,

                    'expected_return_at' =>
                    $request->expected_return_at ?: null,

                    'condition_before' =>
                    $asset->condition,

                    'purpose' =>
                    $lockedRequest->purpose,

                    'notes' =>
                    $lockedRequest->notes,

                    'approved_by' =>
                    'Muhamad Ariel Saputra',

                    'document_number' =>
                    $documentNumber,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Update sequence
                |--------------------------------------------------------------------------
                */

                $sequence->update([
                    'last_number' => $nextNumber,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Update asset
                |--------------------------------------------------------------------------
                */

                $asset->update([
                    'status' =>
                    Asset::STATUS_IN_USE,

                    'original_location' =>
                    $asset->location,

                    'location' =>
                    'Dipinjam oleh: ' .
                        $lockedRequest->borrower_name,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Update request
                |--------------------------------------------------------------------------
                */

                $lockedRequest->update([
                    'status' => 'approved',
                    'loan_id' => $loan->id,
                ]);

                return $loan;
            });
        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Gagal approve: ' .
                    $e->getMessage()
            );
        }

        return redirect()
            ->route('loan-requests.index')
            ->with(
                'success',
                "Request disetujui. Loan untuk {$loanRequest->borrower_name} berhasil dibuat dengan nomor {$loan->document_number}."
            );
    }

    /**
     * Reject request
     */
    public function reject(
        Request $request,
        LoanRequest $loanRequest
    ) {
        if ($loanRequest->status !== 'pending') {
            return back()->with(
                'error',
                'Request ini sudah diproses.'
            );
        }

        $request->validate([
            'reject_reason' =>
            'required|string|max:500',
        ], [
            'reject_reason.required' =>
            'Alasan penolakan wajib diisi.',
        ]);

        $loanRequest->update([
            'status' =>
            'rejected',

            'reject_reason' =>
            $request->reject_reason,
        ]);

        return redirect()
            ->route('loan-requests.index')
            ->with(
                'success',
                "Request dari {$loanRequest->borrower_name} telah ditolak."
            );
    }
}
