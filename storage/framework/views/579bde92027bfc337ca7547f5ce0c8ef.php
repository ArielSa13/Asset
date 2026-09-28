```blade
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Request Peminjaman Asset</title>

    
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

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

        

        <div class="request-header">

            <div class="d-flex align-items-center gap-3">

                <div
                    class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width:52px;height:52px;"
                >
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


        

        <div class="request-body">

            
            <?php if(session('success')): ?>

                <div class="alert alert-success">

                    <i class="bi bi-check-circle me-2"></i>

                    <?php echo e(session('success')); ?>


                </div>

            <?php endif; ?>


            
            <?php if(session('error')): ?>

                <div class="alert alert-danger">

                    <i class="bi bi-exclamation-circle me-2"></i>

                    <?php echo e(session('error')); ?>


                </div>

            <?php endif; ?>


            
            <?php if($errors->any()): ?>

                <div class="alert alert-danger">

                    <div class="fw-bold mb-2">
                        Terdapat kesalahan:
                    </div>

                    <ul class="mb-0">

                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <li>
                                <?php echo e($error); ?>

                            </li>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </ul>

                </div>

            <?php endif; ?>


            

            <form
                id="loanRequestForm"
                action="<?php echo e(route('loan-requests.public.store')); ?>"
                method="POST"
            >

                <?php echo csrf_field(); ?>


                

                <div class="section-title">

                    <i class="bi bi-person me-2"></i>

                    Data Peminjam

                </div>


                <div class="row g-3 mb-4">

                    
                    <div class="col-md-6">

                        <label class="form-label">

                            Nama Lengkap

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="borrower_name"
                            value="<?php echo e(old('borrower_name')); ?>"
                            class="form-control <?php $__errorArgs = ['borrower_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Masukkan nama lengkap"
                            required
                        >

                        <?php $__errorArgs = ['borrower_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>

                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>


                    
                    <div class="col-md-6">

                        <label class="form-label">

                            Jabatan

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="borrower_position"
                            value="<?php echo e(old('borrower_position')); ?>"
                            class="form-control <?php $__errorArgs = ['borrower_position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Contoh: IT Support, Staff, Supervisor"
                            required
                        >

                        <?php $__errorArgs = ['borrower_position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>

                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>


                    
                    <div class="col-md-6">

                        <label class="form-label">

                            Divisi

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="borrower_department"
                            value="<?php echo e(old('borrower_department')); ?>"
                            class="form-control <?php $__errorArgs = ['borrower_department'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Contoh: IT"
                            required
                        >

                        <?php $__errorArgs = ['borrower_department'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>

                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>


                    
                    <div class="col-md-6">

                        <label class="form-label">
                            Nomor HP
                        </label>

                        <input
                            type="text"
                            name="borrower_phone"
                            value="<?php echo e(old('borrower_phone')); ?>"
                            class="form-control <?php $__errorArgs = ['borrower_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Contoh: 08123456789"
                        >

                        <?php $__errorArgs = ['borrower_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>

                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                </div>


                

                <div class="section-title">

                    <i class="bi bi-box-seam me-2"></i>

                    Pilih Asset

                </div>


                <?php if($assets->isEmpty()): ?>

                    <div class="alert alert-warning mb-4">

                        <i class="bi bi-exclamation-triangle me-2"></i>

                        Saat ini tidak ada asset yang tersedia untuk dipinjam.

                    </div>

                <?php else: ?>


                    

                    <div class="mb-3">

                        <div class="d-flex flex-wrap gap-2">

                            
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary category-filter active"
                                data-category="all"
                            >

                                Semua

                                <span class="badge bg-secondary ms-1">
                                    <?php echo e($assets->count()); ?>

                                </span>

                            </button>


                            
                            <?php $__currentLoopData = $assets->groupBy(
                                    fn ($asset) =>
                                        $asset->assetCategory->name ?? 'Lainnya'
                                ); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $categoryAssets): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php
                                    $categorySlug = \Illuminate\Support\Str::slug($category);
                                ?>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary category-filter"
                                    data-category="<?php echo e($categorySlug); ?>"
                                >

                                    <?php echo e($category); ?>


                                    <span class="badge bg-secondary ms-1">
                                        <?php echo e($categoryAssets->count()); ?>

                                    </span>

                                </button>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>

                    </div>


                    

                    <div
                        class="asset-list mb-2"
                        id="assetList"
                    >

                        <?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php

                                $categoryName =
                                    $asset->assetCategory->name ?? 'Lainnya';

                                $categorySlug =
                                    \Illuminate\Support\Str::slug($categoryName);

                            ?>


                            <label
                                class="asset-option"
                                data-category="<?php echo e($categorySlug); ?>"
                            >

                                <div class="d-flex align-items-start gap-3">

                                    
                                    <div class="pt-1">

                                        <input
                                            type="radio"
                                            name="asset_id"
                                            value="<?php echo e($asset->id); ?>"
                                            class="form-check-input asset-radio"
                                            <?php echo e(old('asset_id') == $asset->id ? 'checked' : ''); ?>

                                            required
                                        >

                                    </div>


                                    
                                    <div class="flex-grow-1">

                                        
                                        <div class="fw-semibold">

                                            <?php echo e($asset->name); ?>


                                        </div>


                                        
                                        <div class="small text-muted mt-1">

                                            <?php if($asset->code): ?>

                                                <span class="me-3">

                                                    <i class="bi bi-upc-scan me-1"></i>

                                                    <?php echo e($asset->code); ?>


                                                </span>

                                            <?php endif; ?>


                                            <?php if($asset->brand): ?>

                                                <span class="me-3">

                                                    <i class="bi bi-tag me-1"></i>

                                                    <?php echo e($asset->brand); ?>


                                                </span>

                                            <?php endif; ?>


                                            <?php if($asset->model): ?>

                                                <span>

                                                    <i class="bi bi-cpu me-1"></i>

                                                    <?php echo e($asset->model); ?>


                                                </span>

                                            <?php endif; ?>

                                        </div>


                                        
                                        <div class="mt-2">

                                            <span class="badge text-bg-success">

                                                Tersedia

                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </label>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                        
                        <div
                            id="noAssetFound"
                            class="alert alert-light border text-center mt-2"
                        >

                            <i class="bi bi-search me-2"></i>

                            Tidak ada asset pada kategori ini.

                        </div>

                    </div>


                    
                    <?php $__errorArgs = ['asset_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                        <div class="text-danger small mb-3">

                            <?php echo e($message); ?>


                        </div>

                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <?php endif; ?>


                

                <div class="section-title mt-4">

                    <i class="bi bi-clipboard-text me-2"></i>

                    Keperluan Peminjaman

                </div>


                <div class="mb-4">

                    <label class="form-label">

                        Keperluan / Tujuan

                        <span class="text-danger">*</span>

                    </label>


                    <textarea
                        name="purpose"
                        rows="3"
                        class="form-control <?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="Jelaskan tujuan penggunaan asset..."
                        required
                    ><?php echo e(old('purpose')); ?></textarea>


                    <?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                        <div class="invalid-feedback">
                            <?php echo e($message); ?>

                        </div>

                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                </div>


                

                <div class="mb-4">

                    <label class="form-label">
                        Catatan
                    </label>


                    <textarea
                        name="notes"
                        rows="3"
                        class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="Catatan tambahan jika diperlukan..."
                    ><?php echo e(old('notes')); ?></textarea>


                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                        <div class="invalid-feedback">
                            <?php echo e($message); ?>

                        </div>

                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                </div>


                

                <div class="section-title">

                    <i class="bi bi-pen me-2"></i>

                    Tanda Tangan Peminjam

                </div>


                <div class="mb-4">

                    <div class="signature-wrapper">

                        <canvas id="signatureCanvas"></canvas>

                    </div>


                    <input
                        type="hidden"
                        name="borrower_signature"
                        id="borrower_signature"
                    >


                    <div class="d-flex justify-content-between align-items-center mt-2">

                        <div class="signature-info">

                            Silakan tanda tangan menggunakan mouse,
                            touchpad, atau layar sentuh.

                        </div>


                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger"
                            id="clearSignature"
                        >

                            <i class="bi bi-eraser me-1"></i>

                            Hapus

                        </button>

                    </div>


                    <?php $__errorArgs = ['borrower_signature'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                        <div class="text-danger small mt-2">

                            <?php echo e($message); ?>


                        </div>

                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                </div>


                

                <div class="d-grid">

                    <button
                        type="submit"
                        id="submitButton"
                        class="btn btn-primary btn-lg"
                        <?php echo e($assets->isEmpty() ? 'disabled' : ''); ?>

                    >

                        <i class="bi bi-send me-2"></i>

                        Ajukan Peminjaman

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>




<script src="https://cdn.jsdelivr.net/npm/signature_pad@5.0.4/dist/signature_pad.umd.min.js"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {

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
            signaturePad.isEmpty()
                ? null
                : signaturePad.toData();


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
        function () {

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
        function (event) {

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
        function (radio) {

            radio.addEventListener(
                'change',
                function () {

                    /*
                    | Hapus selected dari semua
                    */

                    document
                        .querySelectorAll('.asset-option')
                        .forEach(
                            function (option) {

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
        function (button) {

            button.addEventListener(
                'click',
                function () {

                    /*
                    | Kategori yang dipilih
                    */

                    const selectedCategory =
                        this.dataset.category;


                    /*
                    | Update tombol active
                    */

                    filterButtons.forEach(
                        function (btn) {

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
                        function (asset) {

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
<?php /**PATH /www/wwwroot/asset.adb.web.id/resources/views/loan-requests/form.blade.php ENDPATH**/ ?>