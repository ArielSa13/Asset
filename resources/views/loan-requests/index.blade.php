@extends('layouts.app')
@section('title', 'Request Peminjaman')
@section('page-title', 'Request Peminjaman')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            @if ($pendingCount > 0)
                <span class="badge bg-warning text-dark fs-6">
                    <i class="bi bi-clock me-1"></i> {{ $pendingCount }} menunggu persetujuan
                </span>
            @else
                <span class="badge bg-success fs-6"><i class="bi bi-check-all me-1"></i> Semua sudah diproses</span>
            @endif
        </div>
        <a href="{{ route('loan-requests.public.form') }}" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Form Publik
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="font-size:.85rem">
                <thead class="table-light">
                    <tr>
                        <th>Peminjam</th>
                        <th>Asset</th>
                        <th>Keperluan</th>
                        <th>Waktu Request</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $req->borrower_name }}</div>
                                <div class="text-muted small">{{ $req->borrower_department ?? '—' }}</div>
                                @if ($req->borrower_phone)
                                    <div class="text-muted small"><i
                                            class="bi bi-telephone me-1"></i>{{ $req->borrower_phone }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $req->asset->name ?? '—' }}</div>
                                <code class="small">{{ $req->asset->code ?? '' }}</code>
                            </td>
                            <td>
                                <div>{{ $req->purpose }}</div>
                                @if ($req->notes)
                                    <div class="text-muted small">{{ $req->notes }}</div>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $req->created_at->format('d M Y, H:i') }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $req->status_badge }}">{{ $req->status_label }}</span>
                                @if ($req->status === 'rejected' && $req->reject_reason)
                                    <div class="text-muted small mt-1">{{ $req->reject_reason }}</div>
                                @endif
                                @if ($req->status === 'approved' && $req->loan)
                                    <div class="mt-1">
                                        <a href="{{ route('loans.show', $req->loan) }}" class="small text-primary">
                                            <i class="bi bi-link-45deg"></i> Lihat Loan
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($req->status === 'pending')
                                    <div class="d-flex gap-1 justify-content-center">
                                        {{-- Approve --}}
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#approveModal{{ $req->id }}" title="Setujui">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        {{-- Reject --}}
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $req->id }}" title="Tolak">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>

                                    {{-- Modal Approve --}}
                                    <div class="modal fade" id="approveModal{{ $req->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white py-2">
                                                    <h6 class="modal-title mb-0">Setujui Request</h6>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('loan-requests.approve', $req) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body text-start">
                                                        <p class="small mb-3">
                                                            Setujui peminjaman <strong>{{ $req->asset->name }}</strong>
                                                            untuk <strong>{{ $req->borrower_name }}</strong>?
                                                        </p>
                                                        <div class="mb-2">
                                                            <label class="form-label small fw-semibold">Estimasi
                                                                Pengembalian <span
                                                                    class="text-muted fw-normal">(opsional)</span></label>
                                                            <input type="date" name="expected_return_at"
                                                                class="form-control form-control-sm"
                                                                min="{{ now()->addDay()->format('Y-m-d') }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer py-2">
                                                        <button type="button" class="btn btn-sm btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="bi bi-check me-1"></i> Setujui & Buat Loan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Reject --}}
                                    <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white py-2">
                                                    <h6 class="modal-title mb-0">Tolak Request</h6>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('loan-requests.reject', $req) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body text-start">
                                                        <p class="small mb-2">Alasan penolakan untuk
                                                            <strong>{{ $req->borrower_name }}</strong>:</p>
                                                        <textarea name="reject_reason" class="form-control form-control-sm" rows="3"
                                                            placeholder="e.g. Asset sedang dalam perbaikan, silakan hubungi IT langsung" required></textarea>
                                                    </div>
                                                    <div class="modal-footer py-2">
                                                        <button type="button" class="btn btn-sm btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-x me-1"></i> Tolak Request
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Belum ada request peminjaman
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($requests->hasPages())
            <div class="card-footer bg-white">{{ $requests->links() }}</div>
        @endif
    </div>
@endsection
