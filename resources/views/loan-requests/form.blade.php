```blade
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

        body {
            min-height: 100vh;
            margin: 0;
            padding: 30px 15px;
            background: linear-gradient(135deg, #0d6efd, #6f42c1);
        }

        .request-card {
            width: 100%;
            max-width: 850px;
            margin: 0 auto;
            border: 0;
            border-radius: 20px;
            overflow: hidden;
        }

        .request-header {
            padding: 30px;
            background: #ffffff;
            border-bottom: 1px solid #eeeeee;
        }

        .request-body {
            padding: 30px;
            background: #ffffff;
        }

        .section-title {
            margin-bottom: 15px;
            color: #212529;
            font-size: 1rem;
            font-weight: 700;
        }

        /* =========================================================
           ASSET FILTER
        ========================================================= */

        .category-filter {
            cursor: pointer;
            transition: all .2s ease;
        }

        .category-filter:hover {
            transform: translateY(-1px);
        }

        .category-filter.active {
            color: #ffffff !important;
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
        }

        /* =========================================================
           ASSET LIST
        ========================================================= */

        .asset-list {
            max-height: 400px;
            padding-right: 4px;
            overflow-y: auto;
        }

        .asset-option {
            display: block;
            margin-bottom: 10px;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            background: #ffffff;
            cursor: pointer;
            transition: all .2s ease;
        }

        .asset-option:hover {
            border-color: #0d6efd;
            background-color: #f8fbff;
        }

        .asset-option.selected {
            border-color: #0d6efd;
            background-color: #eaf3ff;
            box-shadow: 0 0 0 1px rgba(13, 110, 253, .15);
        }

        .asset-option.hidden {
            display: none !important;
        }

        .asset-radio {
            cursor: pointer;
        }

        /* =========================================================
           SIGNATURE
        ========================================================= */

        .signature-wrapper {
            width: 100%;
            overflow: hidden;
            border: 1px solid #ced4da;
            border-radius: 10px;
            background-color: #ffffff;
        }

        #signatureCanvas {
            display: block;
            width: 100%;
            height: 200px;
            touch-action: none;
            cursor: crosshair;
        }

        .signature-info {
            color: #6c757d;
            font-size: .85rem;
        }

        /* =========================================================
           SCROLLBAR
        ========================================================= */

        .asset-list::-webkit-scrollbar {
            width: 6px;
        }

        .asset-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .asset-list::-webkit-scrollbar-thumb {
            background: #adb5bd;
            border-radius: 10px;
        }

        /* =========================================================
           EMPTY FILTER RESULT
        ========================================================= */

        #noAssetFound {
            display: none;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 576px) {

            body {
                padding: 15px 10px;
            }

            .request-header,
            .request-body {
                padding: 20px;
            }

            .request-card {
                border-radius: 15px;
            }

            #signatureCanvas {
                height: 180px;
            }

        }
    </style>
</head>


<body>

    <div class="container">

        <div class="card shadow-lg request-card">

            {{-- =====================================================
             HEADER
        ====================================================== --}}

            <div class="request-header">

                <div class="d-flex align-items-center gap-3">

                    <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:52px;height:52px;">
                        <i class="bi bi-laptop fs-4"></i>
                    </div>

                    <div>

                        <h3 class="mb-1 fw-bold">
                            Request Peminjaman Asset
                        </h3>

                        <p class="text-muted mb-0">
                            Silakan isi data peminjaman dengan lengkap.
                        </p>

                    </div>

                </div>

            </div>


            {{-- =====================================================
             BODY
        ====================================================== --}}

            <div class="request-body">

                {{-- SUCCESS --}}
                @if (session('success'))
                    <div class="alert alert-success">

                        <i class="bi bi-check-circle me-2"></i>

                        {{ session('success') }}

                    </div>
                @endif


                {{-- ERROR --}}
                @if (session('error'))
                    <div class="alert alert-danger">

                        <i class="bi bi-exclamation-circle me-2"></i>

                        {{ session('error') }}

                    </div>
                @endif


                {{-- VALIDATION ERROR --}}
                @if ($errors->any())

                    <div class="alert alert-danger">

                        <div class="fw-bold mb-2">
                            Terdapat kesalahan:
                        </div>

                        <ul class="mb-0">

                            @foreach ($errors->all() as $error)
                                <li>
                                    {{ $error }}
                                </li>
                            @endforeach

                        </ul>

                    </div>

                @endif


                {{-- =================================================
                 FORM
            ================================================== --}}

                <form id="loanRequestForm" action="{{ route('loan-requests.public.store') }}" method="POST">

                    @csrf


                    {{-- =================================================
                     DATA PEMINJAM
                ================================================== --}}

                    <div class="section-title">

                        <i class="bi bi-person me-2"></i>

                        Data Peminjam

                    </div>


                    <div class="row g-3 mb-4">

                        {{-- Nama --}}
                        <div class="col-md-6">

                            <label class="form-label">

                                Nama Lengkap

                                <span class="text-danger">*</span>

                            </label>

                            <input type="text" name="borrower_name" value="{{ old('borrower_name') }}"
                                class="form-control @error('borrower_name') is-invalid @enderror"
                                placeholder="Masukkan nama lengkap" required>

                            @error('borrower_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Jabatan --}}
                        <div class="col-md-6">

                            <label class="form-label">

                                Jabatan

                                <span class="text-danger">*</span>

                            </label>

                            <input type="text" name="borrower_position" value="{{ old('borrower_position') }}"
                                class="form-control @error('borrower_position') is-invalid @enderror"
                                placeholder="Contoh: IT Support, Staff, Supervisor" required>

                            @error('borrower_position')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Divisi --}}
                        <div class="col-md-6">

                            <label class="form-label">

                                Divisi

                                <span class="text-danger">*</span>

                            </label>

                            <input type="text" name="borrower_department" value="{{ old('borrower_department') }}"
                                class="form-control @error('borrower_department') is-invalid @enderror"
                                placeholder="Contoh: IT" required>

                            @error('borrower_department')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Nomor HP --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Nomor HP
                            </label>

                            <input type="text" name="borrower_phone" value="{{ old('borrower_phone') }}"
                                class="form-control @error('borrower_phone') is-invalid @enderror"
                                placeholder="Contoh: 08123456789">

                            @error('borrower_phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>


                    {{-- =================================================
                     PILIH ASSET
                ================================================== --}}

                    <div class="section-title">

                        <i class="bi bi-box-seam me-2"></i>

                        Pilih Asset

                    </div>


                    @if ($assets->isEmpty())

                        <div class="alert alert-warning mb-4">

                            <i class="bi bi-exclamation-triangle me-2"></i>

                            Saat ini tidak ada asset yang tersedia untuk dipinjam.

                        </div>
                    @else
                        {{-- =================================================
                         FILTER KATEGORI
                    ================================================== --}}

                        <div class="mb-3">

                            <div class="d-flex flex-wrap gap-2">

                                {{-- Semua --}}
                                <button type="button" class="btn btn-sm btn-outline-primary category-filter active"
                                    data-category="all">

                                    Semua

                                    <span class="badge bg-secondary ms-1">
                                        {{ $assets->count() }}
                                    </span>

                                </button>


                                {{-- Kategori --}}
                                @foreach ($assets->groupBy(fn($asset) => $asset->assetCategory->name ?? 'Lainnya') as $category => $categoryAssets)
                                    @php
                                        $categorySlug = \Illuminate\Support\Str::slug($category);
                                    @endphp

                                    <button type="button" class="btn btn-sm btn-outline-primary category-filter"
                                        data-category="{{ $categorySlug }}">

                                        {{ $category }}

                                        <span class="badge bg-secondary ms-1">
                                            {{ $categoryAssets->count() }}
                                        </span>

                                    </button>
                                @endforeach

                            </div>

                        </div>


                        {{-- =================================================
                         DAFTAR ASSET
                    ================================================== --}}

                        <div class="asset-list mb-2" id="assetList">

                            @foreach ($assets as $asset)
                                @php

                                    $categoryName = $asset->assetCategory->name ?? 'Lainnya';

                                    $categorySlug = \Illuminate\Support\Str::slug($categoryName);

                                @endphp


                                <label class="asset-option" data-category="{{ $categorySlug }}">

                                    <div class="d-flex align-items-start gap-3">

                                        {{-- Radio --}}
                                        <div class="pt-1">

                                            <input type="radio" name="asset_id" value="{{ $asset->id }}"
                                                class="form-check-input asset-radio"
                                                {{ old('asset_id') == $asset->id ? 'checked' : '' }} required>

                                        </div>


                                        {{-- Detail --}}
                                        <div class="flex-grow-1">

                                            {{-- Nama --}}
                                            <div class="fw-semibold">

                                                {{ $asset->name }}

                                            </div>


                                            {{-- Informasi --}}
                                            <div class="small text-muted mt-1">

                                                @if ($asset->code)
                                                    <span class="me-3">

                                                        <i class="bi bi-upc-scan me-1"></i>

                                                        {{ $asset->code }}

                                                    </span>
                                                @endif


                                                @if ($asset->brand)
                                                    <span class="me-3">

                                                        <i class="bi bi-tag me-1"></i>

                                                        {{ $asset->brand }}

                                                    </span>
                                                @endif


                                                @if ($asset->model)
                                                    <span>

                                                        <i class="bi bi-cpu me-1"></i>

                                                        {{ $asset->model }}

                                                    </span>
                                                @endif

                                            </div>


                                            {{-- Status --}}
                                            <div class="mt-2">

                                                <span class="badge text-bg-success">

                                                    Tersedia

                                                </span>

                                            </div>

                                        </div>

                                    </div>

                                </label>
                            @endforeach


                            {{-- Tidak ditemukan --}}
                            <div id="noAssetFound" class="alert alert-light border text-center mt-2">

                                <i class="bi bi-search me-2"></i>

                                Tidak ada asset pada kategori ini.

                            </div>

                        </div>


                        {{-- Validation --}}
                        @error('asset_id')
                            <div class="text-danger small mb-3">

                                {{ $message }}

                            </div>
                        @enderror

                    @endif


                    {{-- =================================================
                     KEPERLUAN
                ================================================== --}}

                    <div class="section-title mt-4">

                        <i class="bi bi-clipboard-text me-2"></i>

                        Keperluan Peminjaman

                    </div>


                    <div class="mb-4">

                        <label class="form-label">

                            Keperluan / Tujuan

                            <span class="text-danger">*</span>

                        </label>


                        <textarea name="purpose" rows="3" class="form-control @error('purpose') is-invalid @enderror"
                            placeholder="Jelaskan tujuan penggunaan asset..." required>{{ old('purpose') }}</textarea>


                        @error('purpose')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                     CATATAN
                ================================================== --}}

                    <div class="mb-4">

                        <label class="form-label">
                            Catatan
                        </label>


                        <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"
                            placeholder="Catatan tambahan jika diperlukan...">{{ old('notes') }}</textarea>


                        @error('notes')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                     TANDA TANGAN
                ================================================== --}}

                    <div class="section-title">

                        <i class="bi bi-pen me-2"></i>

                        Tanda Tangan Peminjam

                    </div>


                    <div class="mb-4">

                        <div class="signature-wrapper">

                            <canvas id="signatureCanvas"></canvas>

                        </div>


                        <input type="hidden" name="borrower_signature" id="borrower_signature">


                        <div class="d-flex justify-content-between align-items-center mt-2">

                            <div class="signature-info">

                                Silakan tanda tangan menggunakan mouse,
                                touchpad, atau layar sentuh.

                            </div>


                            <button type="button" class="btn btn-sm btn-outline-danger" id="clearSignature">

                                <i class="bi bi-eraser me-1"></i>

                                Hapus

                            </button>

                        </div>


                        @error('borrower_signature')
                            <div class="text-danger small mt-2">

                                {{ $message }}

                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                     SUBMIT
                ================================================== --}}

                    <div class="d-grid">

                        <button type="submit" id="submitButton" class="btn btn-primary btn-lg"
                            {{ $assets->isEmpty() ? 'disabled' : '' }}>

                            <i class="bi bi-send me-2"></i>

                            Ajukan Peminjaman

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =============================================================
     SIGNATURE PAD
============================================================= --}}

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@5.0.4/dist/signature_pad.umd.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | ELEMENT
            |--------------------------------------------------------------------------
            */

            const form =
                document.getElementById('loanRequestForm');

            const canvas =
                document.getElementById('signatureCanvas');

            const signatureInput =
                document.getElementById('borrower_signature');

            const clearButton =
                document.getElementById('clearSignature');

            const submitButton =
                document.getElementById('submitButton');


            /*
            |--------------------------------------------------------------------------
            | SIGNATURE PAD
            |--------------------------------------------------------------------------
            */

            const signaturePad =
                new SignaturePad(canvas, {
                    backgroundColor: 'rgb(255, 255, 255)',
                    penColor: 'rgb(0, 0, 0)',
                    minWidth: 0.8,
                    maxWidth: 2.5
                });


            /*
            |--------------------------------------------------------------------------
            | RESIZE SIGNATURE CANVAS
            |--------------------------------------------------------------------------
            */

            function resizeCanvas() {

                const ratio =
                    Math.max(
                        window.devicePixelRatio || 1,
                        1
                    );

                const rect =
                    canvas.getBoundingClientRect();


                /*
                | Simpan data signature
                */

                const existingData =
                    signaturePad.isEmpty() ?
                    null :
                    signaturePad.toData();


                /*
                | Resize canvas
                */

                canvas.width =
                    rect.width * ratio;

                canvas.height =
                    rect.height * ratio;


                canvas
                    .getContext('2d')
                    .scale(ratio, ratio);


                signaturePad.clear();


                /*
                | Restore signature
                */

                if (existingData) {

                    signaturePad.fromData(existingData);

                }

            }


            resizeCanvas();

            window.addEventListener(
                'resize',
                resizeCanvas
            );


            /*
            |--------------------------------------------------------------------------
            | CLEAR SIGNATURE
            |--------------------------------------------------------------------------
            */

            clearButton.addEventListener(
                'click',
                function() {

                    signaturePad.clear();

                    signatureInput.value = '';

                }
            );


            /*
            |--------------------------------------------------------------------------
            | FORM SUBMIT
            |--------------------------------------------------------------------------
            */

            form.addEventListener(
                'submit',
                function(event) {

                    /*
                    | Pastikan tanda tangan sudah diisi
                    */

                    if (signaturePad.isEmpty()) {

                        event.preventDefault();

                        alert(
                            'Silakan tanda tangan terlebih dahulu.'
                        );

                        return;

                    }


                    /*
                    | Convert signature menjadi PNG Base64
                    */

                    signatureInput.value =
                        signaturePad.toDataURL('image/png');


                    /*
                    | Cegah double submit
                    */

                    submitButton.disabled = true;

                    submitButton.innerHTML = `
                <span
                    class="spinner-border spinner-border-sm me-2"
                    role="status"
                    aria-hidden="true"
                ></span>

                Mengirim...
            `;

                }
            );


            /*
            |--------------------------------------------------------------------------
            | ASSET SELECTION
            |--------------------------------------------------------------------------
            */

            const assetRadios =
                document.querySelectorAll('.asset-radio');


            assetRadios.forEach(
                function(radio) {

                    radio.addEventListener(
                        'change',
                        function() {

                            /*
                            | Hapus selected dari semua
                            */

                            document
                                .querySelectorAll('.asset-option')
                                .forEach(
                                    function(option) {

                                        option.classList.remove(
                                            'selected'
                                        );

                                    }
                                );


                            /*
                            | Tambahkan selected
                            */

                            const selectedOption =
                                radio.closest(
                                    '.asset-option'
                                );


                            if (selectedOption) {

                                selectedOption.classList.add(
                                    'selected'
                                );

                            }

                        }
                    );


                    /*
                    | Restore selected
                    | ketika validation gagal
                    */

                    if (radio.checked) {

                        const selectedOption =
                            radio.closest(
                                '.asset-option'
                            );


                        if (selectedOption) {

                            selectedOption.classList.add(
                                'selected'
                            );

                        }

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | CATEGORY FILTER
            |--------------------------------------------------------------------------
            */

            const filterButtons =
                document.querySelectorAll(
                    '.category-filter'
                );

            const assetOptions =
                document.querySelectorAll(
                    '.asset-option'
                );

            const noAssetFound =
                document.getElementById(
                    'noAssetFound'
                );


            filterButtons.forEach(
                function(button) {

                    button.addEventListener(
                        'click',
                        function() {

                            /*
                            | Kategori yang dipilih
                            */

                            const selectedCategory =
                                this.dataset.category;


                            /*
                            | Update tombol active
                            */

                            filterButtons.forEach(
                                function(btn) {

                                    btn.classList.remove(
                                        'active'
                                    );

                                }
                            );


                            this.classList.add(
                                'active'
                            );


                            /*
                            | Hitung asset yang tampil
                            */

                            let visibleCount = 0;


                            /*
                            | Filter asset
                            */

                            assetOptions.forEach(
                                function(asset) {

                                    const assetCategory =
                                        asset.dataset.category;


                                    const shouldShow =
                                        selectedCategory === 'all' ||
                                        assetCategory === selectedCategory;


                                    if (shouldShow) {

                                        asset.classList.remove(
                                            'hidden'
                                        );

                                        visibleCount++;

                                    } else {

                                        asset.classList.add(
                                            'hidden'
                                        );

                                    }

                                }
                            );


                            /*
                            | Pesan jika kosong
                            */

                            if (visibleCount === 0) {

                                noAssetFound.style.display =
                                    'block';

                            } else {

                                noAssetFound.style.display =
                                    'none';

                            }

                        }
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | DEFAULT FILTER
            |--------------------------------------------------------------------------
            */

            const defaultFilter =
                document.querySelector(
                    '.category-filter.active'
                );


            if (defaultFilter) {

                defaultFilter.click();

            }

        });
    </script>

</body>

</html>
```
