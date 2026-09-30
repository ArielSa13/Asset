<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Request Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fa;
        }

        .tracker {
            max-width: 760px;
            margin: 50px auto;
        }

        .step {
            position: relative;
            padding-left: 48px;
            padding-bottom: 28px;
        }

        .step:last-child {
            padding-bottom: 0;
        }

        .step:not(:last-child)::before {
            content: "";
            position: absolute;
            left: 15px;
            top: 32px;
            width: 2px;
            height: calc(100% - 8px);
            background: #dee2e6;
        }

        .step.done:not(:last-child)::before {
            background: #198754;
        }

        .step-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e9ecef;
            color: #6c757d;
        }

        .step.done .step-icon {
            background: #198754;
            color: #fff;
        }

        .step.active .step-icon {
            background: #ffc107;
            color: #212529;
        }
    </style>
</head>

<body>
    <div class="container tracker">
        <div class="text-center mb-4">
            <div class="mb-2">
                <i class="bi bi-box-seam fs-1 text-primary"></i>
            </div>
            <h3 class="fw-bold mb-1">Lacak Request Peminjaman</h3>
            <p class="text-muted mb-0">Masukkan nomor request untuk melihat status peminjaman.</p>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('loan-requests.track') }}">
                    <label class="form-label fw-semibold">Nomor Request</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="request_number" class="form-control" value="{{ $requestNumber }}"
                            placeholder="Contoh: REQ-001102026" required>
                        <button class="btn btn-primary px-4" type="submit">Lacak</button>
                    </div>
                </form>
            </div>
        </div>

        @if ($requestNumber !== '' && !$loanRequest)
            <div class="alert alert-warning border-0 shadow-sm">
                <i class="bi bi-exclamation-circle me-2"></i>
                Nomor request <strong>{{ $requestNumber }}</strong> tidak ditemukan.
            </div>
        @endif

        @if ($loanRequest)
            @php
                $isApproved = $loanRequest->status === 'approved';
                $isRejected = $loanRequest->status === 'rejected';
                $isPending = $loanRequest->status === 'pending';
                $isReturned = $loanRequest->loan && $loanRequest->loan->returned_at;
            @endphp

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <small class="text-muted d-block">Nomor Request</small>
                            <h4 class="fw-bold mb-0">{{ $loanRequest->request_number }}</h4>
                        </div>
                        <span class="badge bg-{{ $loanRequest->status_badge }} fs-6">
                            {{ $loanRequest->status_label }}
                        </span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Asset</small>
                            <strong>{{ $loanRequest->asset->name ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Peminjam</small>
                            <strong>{{ $loanRequest->borrower_name }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-clock-history me-2"></i>
                        Status Request
                    </h5>

                    <div class="step done">
                        <div class="step-icon"><i class="bi bi-check-lg"></i></div>
                        <strong>Request dibuat</strong>
                        <div class="text-muted small">Permintaan peminjaman berhasil diterima sistem.</div>
                    </div>

                    <div class="step {{ $isPending ? 'active' : 'done' }}">
                        <div class="step-icon">
                            @if ($isPending)
                                <i class="bi bi-hourglass-split"></i>
                            @else
                                <i class="bi bi-check-lg"></i>
                            @endif
                        </div>
                        <strong>Menunggu persetujuan IT Support</strong>
                        <div class="text-muted small">
                            @if ($isPending)
                                Request sedang menunggu pemeriksaan dan persetujuan.
                            @else
                                Request telah selesai diproses.
                            @endif
                        </div>
                    </div>

                    @if ($isRejected)
                        <div class="step done">
                            <div class="step-icon"><i class="bi bi-x-lg"></i></div>
                            <strong>Request ditolak</strong>
                            <div class="text-danger small">
                                {{ $loanRequest->reject_reason ?: 'Request tidak disetujui.' }}
                            </div>
                        </div>
                    @else
                        <div class="step {{ $isApproved ? 'done' : '' }}">
                            <div class="step-icon">
                                @if ($isApproved)
                                    <i class="bi bi-check-lg"></i>
                                @else
                                    <i class="bi bi-circle"></i>
                                @endif
                            </div>
                            <strong>Request disetujui</strong>
                            <div class="text-muted small">
                                @if ($isApproved)
                                    Request telah disetujui oleh IT Support.
                                @else
                                    Menunggu persetujuan.
                                @endif
                            </div>
                        </div>

                        <div class="step {{ $isApproved ? 'done' : '' }}">
                            <div class="step-icon">
                                @if ($isApproved)
                                    <i class="bi bi-box-seam"></i>
                                @else
                                    <i class="bi bi-circle"></i>
                                @endif
                            </div>
                            <strong>Asset diserahkan</strong>
                            <div class="text-muted small">
                                @if ($isApproved)
                                    Peminjaman telah dibuat dan asset tercatat sebagai sedang digunakan.
                                @else
                                    Akan aktif setelah request disetujui.
                                @endif
                            </div>
                        </div>

                        <div class="step {{ $isReturned ? 'done' : '' }}">
                            <div class="step-icon">
                                @if ($isReturned)
                                    <i class="bi bi-check-lg"></i>
                                @else
                                    <i class="bi bi-circle"></i>
                                @endif
                            </div>
                            <strong>Peminjaman selesai</strong>
                            <div class="text-muted small">
                                @if ($isReturned)
                                    Asset telah dikembalikan.
                                @elseif($isApproved)
                                    Peminjaman sedang berlangsung.
                                @else
                                    Akan aktif setelah asset dikembalikan.
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if ($isApproved && $loanRequest->loan)
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Berita Acara Serah Terima
                        </h5>

                        <div class="mb-3">
                            <small class="text-muted d-block">Nomor BAST</small>
                            <strong>{{ $loanRequest->loan->document_number }}</strong>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('loan-requests.public.bast', $loanRequest->request_number) }}"
                                target="_blank" class="btn btn-outline-primary">
                                <i class="bi bi-eye me-1"></i> Lihat BAST
                            </a>
                            <a href="{{ route('loan-requests.public.bast.download', $loanRequest->request_number) }}"
                                class="btn btn-primary">
                                <i class="bi bi-download me-1"></i> Download BAST
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('loan-requests.public.form') }}" class="text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Request Peminjaman
            </a>
        </div>
    </div>
</body>

</html>
