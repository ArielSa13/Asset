<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Peminjaman Asset</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* =========================================================
           GENERAL
        ========================================================= */
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 30px 15px;
            background: linear-gradient(180deg, #eaf3ff 0, #f8fafc 300px);
            color: #1e293b;
            font-family: Arial, Helvetica, sans-serif;
        }

        .request-card {
            width: 100%;
            max-width: 850px;
            margin: 0 auto;
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
        }

        /* =========================================================
           HEADER
        ========================================================= */
        .request-header {
            padding: 28px 30px;
            background: #fff;
            border-bottom: 1px solid #e9eef5;
        }

        .header-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 14px;
            background: #0d6efd;
            color: #fff;
            font-size: 1.35rem;
            box-shadow: 0 5px 15px rgba(13, 110, 253, .18);
        }

        .header-badge {
            display: inline-block;
            margin-bottom: 4px;
            color: #0d6efd;
            font-size: .67rem;
            font-weight: 700;
            letter-spacing: .08em;
        }

        .request-header h3 {
            color: #172033;
            font-size: 1.45rem;
        }

        .request-header p {
            font-size: .88rem;
        }

        /* =========================================================
           BODY
        ========================================================= */
        .request-body {
            padding: 30px;
            background: #fff;
        }

        /* =========================================================
           SECTION TITLE
        ========================================================= */
        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
            color: #212529;
        }

        .section-number {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 9px;
            background: #eaf3ff;
            color: #0d6efd;
            font-size: .72rem;
            font-weight: 700;
        }

        .section-title-text {
            color: #1e293b;
            font-size: .95rem;
            font-weight: 700;
        }

        .section-subtitle {
            margin-top: 2px;
            color: #94a3b8;
            font-size: .76rem;
            font-weight: 400;
        }

        /* =========================================================
           FORM
        ========================================================= */
        .form-label {
            margin-bottom: 7px;
            color: #334155;
            font-size: .85rem;
            font-weight: 600;
        }

        .form-control {
            min-height: 44px;
            border-color: #dce3eb;
            border-radius: 9px;
            font-size: .88rem;
        }

        textarea.form-control {
            min-height: 105px;
            resize: vertical;
        }

        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .08);
        }

        /* =========================================================
           ASSET FILTER
        ========================================================= */
        .category-filter {
            cursor: pointer;
            border-radius: 8px;
            font-size: .78rem;
            transition: all .15s ease;
        }

        .category-filter:hover {
            transform: translateY(-1px);
        }

        .category-filter.active {
            color: #fff !important;
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
        }

        .category-filter .badge {
            font-size: .62rem;
            font-weight: 600;
        }

        /* =========================================================
           ASSET LIST
        ========================================================= */
        .asset-list {
            max-height: 390px;
            padding: 2px 4px 2px 0;
            overflow-y: auto;
        }

        .asset-option {
            display: block;
            margin-bottom: 8px;
            padding: 13px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
            cursor: pointer;
            transition: all .15s ease;
        }

        .asset-option:last-of-type {
            margin-bottom: 0;
        }

        .asset-option:hover {
            border-color: #86b7fe;
            background: #f8fbff;
        }

        .asset-option.selected {
            border-color: #0d6efd;
            background: #f0f7ff;
            box-shadow: 0 0 0 2px rgba(13, 110, 253, .08);
        }

        .asset-option.hidden {
            display: none !important;
        }

        .asset-radio {
            cursor: pointer;
            margin-top: 2px;
        }

        .asset-option .asset-name {
            color: #1e293b;
            font-size: .9rem;
        }

        .asset-option .asset-meta {
            color: #64748b;
            font-size: .74rem;
        }

        .asset-option .badge {
            font-size: .65rem;
            font-weight: 500;
        }

        /* =========================================================
           SCROLLBAR
        ========================================================= */
        .asset-list::-webkit-scrollbar {
            width: 5px;
        }

        .asset-list::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .asset-list::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        /* =========================================================
           EMPTY FILTER
        ========================================================= */
        #noAssetFound {
            display: none;
        }

        /* =========================================================
           AGREEMENT
        ========================================================= */
        .agreement-card {
            padding: 16px;
            border: 1px solid #dbe4f0;
            border-radius: 12px;
            background: #f8fafc;
        }

        .agreement-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 13px;
        }

        .agreement-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 10px;
            background: rgba(13, 110, 253, .10);
            color: #0d6efd;
            font-size: 1.05rem;
        }

        .agreement-header h6 {
            margin: 0 0 3px;
            color: #1e293b;
            font-size: .9rem;
            font-weight: 700;
        }

        .agreement-header p {
            margin: 0;
            color: #64748b;
            font-size: .76rem;
        }

        .agreement-view-btn {
            width: 100%;
            margin-bottom: 12px;
            border-radius: 9px;
            font-size: .83rem;
        }

        .agreement-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .agreement-check input {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .agreement-check label {
            color: #475569;
            font-size: .82rem;
            line-height: 1.55;
            cursor: pointer;
        }

        .agreement-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 11px;
            padding: 4px 9px;
            border-radius: 20px;
            background: #fff3cd;
            color: #856404;
            font-size: .7rem;
            font-weight: 600;
        }

        .agreement-status.accepted {
            background: #d1e7dd;
            color: #0f5132;
        }

        /* =========================================================
           AGREEMENT DOCUMENT / MODAL
        ========================================================= */
        .agreement-document {
            color: #334155;
            font-size: .9rem;
            line-height: 1.7;
        }

        .agreement-document .document-header {
            margin-bottom: 24px;
            text-align: center;
        }

        .agreement-document .document-header h5 {
            margin-bottom: 5px;
            color: #1e293b;
            font-weight: 700;
        }

        .agreement-document .document-header p {
            margin: 0;
            color: #64748b;
            font-size: .82rem;
        }

        .agreement-document .document-intro {
            margin-bottom: 18px;
            text-align: justify;
        }

        .agreement-document ol {
            padding-left: 23px;
            margin-bottom: 0;
        }

        .agreement-document li {
            margin-bottom: 12px;
            padding-left: 5px;
            text-align: justify;
        }

        .agreement-document .document-closing {
            margin-top: 20px;
            padding: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            text-align: justify;
        }

        /* =========================================================
           SIGNATURE
        ========================================================= */
        .signature-section-locked {
            position: relative;
        }

        .signature-lock-message {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 8px;
            color: #856404;
            font-size: .78rem;
        }

        .signature-lock-message:not(.show) {
            display: none;
        }

        .signature-wrapper {
            position: relative;
            width: 100%;
            overflow: hidden;
            border: 1px dashed #b8c2cc;
            border-radius: 12px;
            background-color: #fff;
            transition: all .2s ease;
        }

        .signature-wrapper.locked {
            background-color: #f1f3f5;
            border-color: #dee2e6;
        }

        .signature-wrapper::after {
            content: "Tanda tangan di area ini";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #adb5bd;
            font-size: .82rem;
            pointer-events: none;
            opacity: .6;
            white-space: nowrap;
        }

        .signature-wrapper.locked::after {
            content: "Tanda tangan terkunci";
        }

        .signature-wrapper.has-signature::after {
            display: none;
        }

        #signatureCanvas {
            display: block;
            width: 100%;
            height: 190px;
            touch-action: none;
            cursor: crosshair;
        }

        .signature-wrapper.locked #signatureCanvas {
            cursor: not-allowed;
        }

        .signature-info {
            color: #94a3b8;
            font-size: .74rem;
        }

        /* =========================================================
           SUBMIT
        ========================================================= */
        .submit-area {
            margin-top: 10px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .submit-area .btn {
            height: 52px;
            border-radius: 11px;
            font-weight: 600;
        }

        .submit-info {
            margin-top: 9px;
            text-align: center;
            color: #94a3b8;
            font-size: .72rem;
        }

        /* =========================================================
           MODAL
        ========================================================= */
        .modal-content {
            border: 0;
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-header {
            padding: 18px 22px;
            border-bottom: 1px solid #e9eef5;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            padding: 14px 22px;
            border-top: 1px solid #e9eef5;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */
        @media (max-width: 576px) {
            body {
                padding: 12px 8px;
            }

            .request-card {
                border-radius: 14px;
            }

            .request-header,
            .request-body {
                padding: 20px;
            }

            .request-header h3 {
                font-size: 1.2rem;
            }

            .request-header p {
                font-size: .78rem;
            }

            .header-icon {
                width: 46px;
                height: 46px;
                border-radius: 12px;
                font-size: 1.15rem;
            }

            .section-number {
                width: 30px;
                height: 30px;
            }

            #signatureCanvas {
                height: 170px;
            }

            .agreement-card {
                padding: 14px;
            }

            .modal-body {
                padding: 18px;
            }

            .agreement-document {
                font-size: .84rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card shadow-sm request-card">
            {{-- HEADER --}}
            <div class="request-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="header-badge">ASSET MANAGEMENT</div>
                        <h3 class="mb-1 fw-bold">Request Peminjaman Asset</h3>
                        <p class="text-muted mb-0">Ajukan peminjaman peralatan kerja dengan mudah.</p>
                    </div>
                    <a href="{{ route('loan-requests.track') }}" class="btn btn-outline-primary btn-sm flex-shrink-0">
                        <i class="bi bi-search me-1"></i>Lacak Request
                    </a>
                </div>
            </div>

            {{-- BODY --}}
            <div class="request-body">
                {{-- SUCCESS --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                {{-- ERROR --}}
                @if (session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                {{-- VALIDATION ERROR --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <div class="fw-bold mb-2">Terdapat kesalahan:</div>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="loanRequestForm" action="{{ route('loan-requests.public.store') }}" method="POST">
                    @csrf

                    {{-- 01 DATA PEMINJAM --}}
                    <div class="section-title">
                        <span class="section-number">01</span>
                        <div>
                            <div class="section-title-text">Data Peminjam</div>
                            <div class="section-subtitle">Lengkapi informasi peminjam asset</div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        {{-- Nama --}}
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="borrower_name" value="{{ old('borrower_name') }}"
                                class="form-control @error('borrower_name') is-invalid @enderror"
                                placeholder="Masukkan nama lengkap" autocomplete="name" required>
                            @error('borrower_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jabatan --}}
                        <div class="col-md-6">
                            <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" name="borrower_position" value="{{ old('borrower_position') }}"
                                class="form-control @error('borrower_position') is-invalid @enderror"
                                placeholder="Contoh: IT Support, Staff, Supervisor" required>
                            @error('borrower_position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Divisi --}}
                        <div class="col-md-6">
                            <label class="form-label">Divisi <span class="text-danger">*</span></label>
                            <input type="text" name="borrower_department" value="{{ old('borrower_department') }}"
                                class="form-control @error('borrower_department') is-invalid @enderror"
                                placeholder="Contoh: IT" required>
                            @error('borrower_department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nomor HP --}}
                        <div class="col-md-6">
                            <label class="form-label">Nomor HP</label>
                            <input type="text" name="borrower_phone" value="{{ old('borrower_phone') }}"
                                class="form-control @error('borrower_phone') is-invalid @enderror"
                                placeholder="Contoh: 08123456789" autocomplete="tel">
                            @error('borrower_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- 02 PILIH ASSET --}}
                    <div class="section-title">
                        <span class="section-number">02</span>
                        <div>
                            <div class="section-title-text">Pilih Asset</div>
                            <div class="section-subtitle">Pilih asset yang tersedia untuk dipinjam</div>
                        </div>
                    </div>

                    @if ($assets->isEmpty())
                        <div class="alert alert-warning mb-4">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Saat ini tidak ada asset yang tersedia untuk dipinjam.
                        </div>
                    @else
                        {{-- FILTER --}}
                        <div class="mb-3">
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary category-filter active"
                                    data-category="all">
                                    Semua <span class="badge bg-secondary ms-1">{{ $assets->count() }}</span>
                                </button>

                                @foreach ($assets->groupBy(fn($asset) => $asset->assetCategory->name ?? 'Lainnya') as $category => $categoryAssets)
                                    @php $categorySlug = \Illuminate\Support\Str::slug($category); @endphp
                                    <button type="button" class="btn btn-sm btn-outline-primary category-filter"
                                        data-category="{{ $categorySlug }}">
                                        {{ $category }} <span
                                            class="badge bg-secondary ms-1">{{ $categoryAssets->count() }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- ASSET LIST --}}
                        <div class="asset-list mb-2" id="assetList">
                            @foreach ($assets as $asset)
                                @php
                                    $categoryName = $asset->assetCategory->name ?? 'Lainnya';
                                    $categorySlug = \Illuminate\Support\Str::slug($categoryName);
                                @endphp

                                <label class="asset-option" data-category="{{ $categorySlug }}">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="pt-1">
                                            <input type="radio" name="asset_id" value="{{ $asset->id }}"
                                                class="form-check-input asset-radio"
                                                {{ old('asset_id') == $asset->id ? 'checked' : '' }} required>
                                        </div>

                                        <div class="flex-grow-1">
                                            <div class="asset-name fw-semibold">{{ $asset->name }}</div>

                                            <div class="asset-meta mt-1">
                                                @if ($asset->code)
                                                    <span class="me-3"><i
                                                            class="bi bi-upc-scan me-1"></i>{{ $asset->code }}</span>
                                                @endif
                                                @if ($asset->brand)
                                                    <span class="me-3"><i
                                                            class="bi bi-tag me-1"></i>{{ $asset->brand }}</span>
                                                @endif
                                                @if ($asset->model)
                                                    <span><i class="bi bi-cpu me-1"></i>{{ $asset->model }}</span>
                                                @endif
                                            </div>

                                            <div class="mt-2">
                                                <span class="badge text-bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Tersedia
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach

                            {{-- EMPTY FILTER --}}
                            <div id="noAssetFound" class="alert alert-light border text-center mt-2">
                                <i class="bi bi-search me-2"></i>Tidak ada asset pada kategori ini.
                            </div>
                        </div>

                        @error('asset_id')
                            <div class="text-danger small mb-3">{{ $message }}</div>
                        @enderror
                    @endif

                    {{-- 03 KEPERLUAN --}}
                    <div class="section-title mt-4">
                        <span class="section-number">03</span>
                        <div>
                            <div class="section-title-text">Keperluan Peminjaman</div>
                            <div class="section-subtitle">Jelaskan tujuan penggunaan asset</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keperluan / Tujuan <span class="text-danger">*</span></label>
                        <textarea name="purpose" rows="3" class="form-control @error('purpose') is-invalid @enderror"
                            placeholder="Contoh: Digunakan untuk pekerjaan operasional, meeting, atau kebutuhan project..." required>{{ old('purpose') }}</textarea>
                        @error('purpose')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 04 PERSETUJUAN --}}
                    <div class="section-title">
                        <span class="section-number">04</span>
                        <div>
                            <div class="section-title-text">Persetujuan</div>
                            <div class="section-subtitle">Baca dan setujui ketentuan sebelum tanda tangan</div>
                        </div>
                    </div>

                    <div class="agreement-card mb-4">
                        <div class="agreement-header">
                            <div class="agreement-icon">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <div>
                                <h6>Perjanjian Penggunaan Peralatan Kerja</h6>
                                <p>Ketentuan penggunaan dan tanggung jawab peminjam.</p>
                            </div>
                        </div>

                        {{-- Buka Perjanjian --}}
                        <button type="button" class="btn btn-outline-primary agreement-view-btn"
                            data-bs-toggle="modal" data-bs-target="#agreementModal">
                            <i class="bi bi-eye me-2"></i>Lihat & Baca Perjanjian
                        </button>

                        {{-- Checkbox --}}
                        <div class="agreement-check">
                            <input type="checkbox" id="agreementAccepted" name="agreement_accepted" value="1"
                                {{ old('agreement_accepted') ? 'checked' : '' }} required>
                            <label for="agreementAccepted">
                                Saya telah membaca, memahami, dan menyetujui <strong>ketentuan penggunaan asset</strong>
                                serta bersedia menggunakan dan menjaga asset sesuai dengan ketentuan yang berlaku.
                            </label>
                        </div>

                        {{-- Status --}}
                        <div id="agreementStatus" class="agreement-status">
                            <i class="bi bi-lock"></i>Belum menyetujui perjanjian
                        </div>
                    </div>

                    {{-- 05 TANDA TANGAN --}}
                    <div class="section-title">
                        <span class="section-number">05</span>
                        <div>
                            <div class="section-title-text">Tanda Tangan Peminjam</div>
                            <div class="section-subtitle">Tanda tangan digital sebagai persetujuan peminjaman</div>
                        </div>
                    </div>

                    <div class="mb-4 signature-section-locked">
                        <div id="signatureLockMessage" class="signature-lock-message show">
                            <i class="bi bi-info-circle"></i>
                            Silakan baca dan setujui perjanjian terlebih dahulu untuk mengaktifkan tanda tangan.
                        </div>

                        <div id="signatureWrapper" class="signature-wrapper locked">
                            <canvas id="signatureCanvas"></canvas>
                        </div>

                        <input type="hidden" name="borrower_signature" id="borrower_signature">

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="signature-info">
                                <i class="bi bi-info-circle me-1"></i>Gunakan mouse, touchpad, atau layar sentuh.
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="clearSignature"
                                disabled>
                                <i class="bi bi-eraser me-1"></i>Hapus
                            </button>
                        </div>

                        @error('borrower_signature')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SUBMIT --}}
                    <div class="submit-area">
                        <button type="submit" id="submitButton" class="btn btn-primary btn-lg w-100"
                            {{ $assets->isEmpty() ? 'disabled' : '' }}>
                            <i class="bi bi-send me-2"></i>Ajukan Peminjaman
                        </button>
                        <div class="submit-info">
                            <i class="bi bi-shield-check me-1"></i>Data akan diproses oleh IT Support.
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- AGREEMENT MODAL --}}
    <div class="modal fade" id="agreementModal" tabindex="-1" aria-labelledby="agreementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                {{-- HEADER --}}
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold" id="agreementModalLabel">PERJANJIAN PENGGUNAAN PERALATAN KERJA
                        </h5>
                        <small class="text-muted">PT. VIVA MEDIA BARU</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- BODY --}}
                <div class="modal-body">
                    <div class="agreement-document">
                        <div class="document-header">
                            <h5>BERITA ACARA SERAH TERIMA PERALATAN KERJA</h5>
                            <p>PT. VIVA MEDIA BARU</p>
                        </div>

                        <div class="document-intro">
                            Dengan mengajukan peminjaman peralatan kerja, peminjam menyatakan telah memahami ketentuan
                            penggunaan dan tanggung jawab atas peralatan kerja yang dipinjam dengan ketentuan sebagai
                            berikut:
                        </div>

                        <ol>
                            <li>Perawatan sehari hari menjadi tanggung jawab pengguna.</li>
                            <li>Kerusakan selama masa garansi ditanggung oleh vendor yang difasilitasi Bagian GA dan
                                diurus oleh Bagian Procurement.</li>
                            <li>Kerusakan dan atau kehilangan sebagian atau seluruh komponen peralatan karena kecelakaan
                                kerja menjadi tanggung jawab perusahaan.</li>
                            <li>Kerusakan dan atau kehilangan sebagian atau seluruh komponen peralatan kerja tersebut
                                diatas akibat kelalaian pengguna menjadi tanggung jawab pihak pengguna sepenuhnya.</li>
                        </ol>

                        <div class="document-closing">
                            Dengan memberikan tanda tangan digital, peminjam menyatakan telah membaca, memahami, dan
                            menyetujui seluruh
                            ketentuan penggunaan peralatan kerja tersebut di atas.
                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="bi bi-check2 me-1"></i>Selesai Membaca
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- BOOTSTRAP --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- SIGNATURE PAD --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@5.0.4/dist/signature_pad.umd.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loanRequestForm');
            const canvas = document.getElementById('signatureCanvas');
            const signatureInput = document.getElementById('borrower_signature');
            const clearButton = document.getElementById('clearSignature');
            const submitButton = document.getElementById('submitButton');
            const agreementCheckbox = document.getElementById('agreementAccepted');
            const agreementStatus = document.getElementById('agreementStatus');
            const signatureWrapper = document.getElementById('signatureWrapper');
            const signatureLockMessage = document.getElementById('signatureLockMessage');

            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)',
                minWidth: 0.8,
                maxWidth: 2.5
            });

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const rect = canvas.getBoundingClientRect();
                const existingData = signaturePad.isEmpty() ? null : signaturePad.toData();

                canvas.width = rect.width * ratio;
                canvas.height = rect.height * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePad.clear();

                if (existingData) {
                    signaturePad.fromData(existingData);
                }
            }

            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            function updateSignatureVisual() {
                if (!signaturePad.isEmpty()) {
                    signatureWrapper.classList.add('has-signature');
                } else {
                    signatureWrapper.classList.remove('has-signature');
                }
            }

            signaturePad.addEventListener('beginStroke', function() {
                signatureWrapper.classList.add('has-signature');
            });

            function updateAgreementState() {
                const accepted = agreementCheckbox.checked;

                if (accepted) {
                    agreementStatus.classList.add('accepted');
                    agreementStatus.innerHTML =
                        `<i class="bi bi-check-circle-fill"></i> Perjanjian telah disetujui`;

                    signatureWrapper.classList.remove('locked');
                    signatureLockMessage.classList.remove('show');
                    clearButton.disabled = false;
                    canvas.style.pointerEvents = 'auto';
                    canvas.style.opacity = '1';

                    if (!{{ $assets->isEmpty() ? 'true' : 'false' }}) {
                        submitButton.disabled = false;
                    }
                } else {
                    agreementStatus.classList.remove('accepted');
                    agreementStatus.innerHTML = `<i class="bi bi-lock"></i> Belum menyetujui perjanjian`;

                    signatureWrapper.classList.add('locked');
                    signatureLockMessage.classList.add('show');
                    clearButton.disabled = true;
                    canvas.style.pointerEvents = 'none';
                    canvas.style.opacity = '.55';

                    if (!signaturePad.isEmpty()) {
                        signaturePad.clear();
                    }

                    signatureInput.value = '';
                    signatureWrapper.classList.remove('has-signature');
                    submitButton.disabled = true;
                }
            }

            updateAgreementState();
            agreementCheckbox.addEventListener('change', updateAgreementState);

            clearButton.addEventListener('click', function() {
                signaturePad.clear();
                signatureInput.value = '';
                signatureWrapper.classList.remove('has-signature');
            });

            form.addEventListener('submit', function(event) {
                if (!agreementCheckbox.checked) {
                    event.preventDefault();
                    alert('Silakan baca dan setujui perjanjian terlebih dahulu.');
                    return;
                }

                if (signaturePad.isEmpty()) {
                    event.preventDefault();
                    alert('Silakan tanda tangan terlebih dahulu.');
                    return;
                }

                signatureInput.value = signaturePad.toDataURL('image/png');
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Mengirim...
                `;
            });

            const assetRadios = document.querySelectorAll('.asset-radio');

            assetRadios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    document.querySelectorAll('.asset-option').forEach(function(option) {
                        option.classList.remove('selected');
                    });

                    const selectedOption = radio.closest('.asset-option');

                    if (selectedOption) {
                        selectedOption.classList.add('selected');
                    }
                });

                if (radio.checked) {
                    const selectedOption = radio.closest('.asset-option');

                    if (selectedOption) {
                        selectedOption.classList.add('selected');
                    }
                }
            });

            const filterButtons = document.querySelectorAll('.category-filter');
            const assetOptions = document.querySelectorAll('.asset-option');
            const noAssetFound = document.getElementById('noAssetFound');

            filterButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const selectedCategory = this.dataset.category;

                    filterButtons.forEach(function(btn) {
                        btn.classList.remove('active');
                    });

                    this.classList.add('active');

                    let visibleCount = 0;

                    assetOptions.forEach(function(asset) {
                        const assetCategory = asset.dataset.category;
                        const shouldShow = selectedCategory === 'all' || assetCategory ===
                            selectedCategory;

                        if (shouldShow) {
                            asset.classList.remove('hidden');
                            visibleCount++;
                        } else {
                            asset.classList.add('hidden');
                        }
                    });

                    noAssetFound.style.display = visibleCount === 0 ? 'block' : 'none';
                });
            });

            const defaultFilter = document.querySelector('.category-filter.active');

            if (defaultFilter) {
                defaultFilter.click();
            }
        });
    </script>
</body>

</html>
