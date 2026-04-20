<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Loan;
use App\Models\LoanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'asset_id'             => 'required|exists:assets,id',
            'borrower_name'        => 'required|string|max:255',
            'borrower_department'  => 'nullable|string|max:255',
            'borrower_phone'       => 'nullable|string|max:50',
            'purpose'              => 'required|string|max:500',
            'notes'                => 'nullable|string|max:1000',
        ], [
            'asset_id.required'       => 'Pilih asset yang ingin dipinjam.',
            'borrower_name.required'  => 'Nama peminjam wajib diisi.',
            'purpose.required'        => 'Keperluan peminjaman wajib diisi.',
        ]);

        // Pastikan asset masih available
        $asset = Asset::findOrFail($validated['asset_id']);
        if ($asset->status !== 'available') {
            return back()->withErrors(['asset_id' => 'Asset ini sudah tidak tersedia.'])->withInput();
        }

        // Cek apakah asset sudah ada pending request
        $existingRequest = LoanRequest::where('asset_id', $validated['asset_id'])
            ->where('status', 'pending')
            ->exists();
        if ($existingRequest) {
            return back()->withErrors(['asset_id' => 'Asset ini sudah ada permintaan peminjaman yang sedang menunggu persetujuan.'])->withInput();
        }

        LoanRequest::create($validated);

        return redirect()->route('loan-requests.public.success');
    }

    /**
     * Halaman sukses setelah submit
     */
    public function publicSuccess()
    {
        return view('loan-requests.success');
    }

    /**
     * List semua request — untuk admin
     */
    public function index()
    {
        $requests = LoanRequest::with(['asset.assetCategory', 'loan'])
            ->latest()
            ->paginate(15);

        $pendingCount = LoanRequest::where('status', 'pending')->count();

        return view('loan-requests.index', compact('requests', 'pendingCount'));
    }

    /**
     * Approve request → buat Loan otomatis
     */
    public function approve(Request $request, LoanRequest $loanRequest)
    {
        if ($loanRequest->status !== 'pending') {
            return back()->with('error', 'Request ini sudah diproses.');
        }

        $request->validate([
            'expected_return_at' => 'nullable|date|after:today',
        ]);

        DB::beginTransaction();
        try {
            // Buat loan
            $loan = Loan::create([
                'asset_id'            => $loanRequest->asset_id,
                'borrower_name'       => $loanRequest->borrower_name,
                'borrower_department' => $loanRequest->borrower_department,
                'borrower_phone'      => $loanRequest->borrower_phone,
                'purpose'             => $loanRequest->purpose,
                'notes'               => $loanRequest->notes,
                'borrowed_at'         => now(),
                'expected_return_at'  => $request->expected_return_at ?: null,
                'condition_before'    => $loanRequest->asset->condition,
                'approved_by'         => 'Muhamad Ariel Saputra',
            ]);

            // Update status asset + lokasi
            $loanRequest->asset->update([
                'status'            => 'in_use',
                'original_location' => $loanRequest->asset->location,
                'location'          => 'Dipinjam oleh: ' . $loanRequest->borrower_name,
            ]);

            // Update request
            $loanRequest->update([
                'status'  => 'approved',
                'loan_id' => $loan->id,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal approve: ' . $e->getMessage());
        }

        return redirect()->route('loan-requests.index')
            ->with('success', "Request disetujui. Loan untuk {$loanRequest->borrower_name} berhasil dibuat.");
    }

    /**
     * Reject request
     */
    public function reject(Request $request, LoanRequest $loanRequest)
    {
        if ($loanRequest->status !== 'pending') {
            return back()->with('error', 'Request ini sudah diproses.');
        }

        $request->validate([
            'reject_reason' => 'required|string|max:500',
        ], [
            'reject_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $loanRequest->update([
            'status'        => 'rejected',
            'reject_reason' => $request->reject_reason,
        ]);

        return redirect()->route('loan-requests.index')
            ->with('success', "Request dari {$loanRequest->borrower_name} telah ditolak.");
    }
}
