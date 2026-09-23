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
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0d6efd, #6f42c1);
            padding: 30px 15px;
        }

        .request-card {
            max-width: 850px;
            margin: 0 auto;
            border: 0;
            border-radius: 20px;
            overflow: hidden;
        }

        .request-header {
            background: #fff;
            padding: 30px;
            border-bottom: 1px solid #eee;
        }

        .request-body {
            background: #fff;
            padding: 30px;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #212529;
        }

        .asset-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .asset-option {
            cursor: pointer;
            transition: .2s;
            border: 1px solid #dee2e6;
        }

        .asset-option:hover {
            border-color: #0d6efd;
            background: #f8fbff;
        }

        .asset-option.selected {
            border-color: #0d6efd;
            background: #eaf3ff;
        }

        .signature-wrapper {
            border: 1px solid #ced4da;
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
        }

        #signatureCanvas {
            display: block;
            width: 100%;
            height: 200px;
            touch-action: none;
            cursor: crosshair;
        }

        .signature-info {
            font-size: .85rem;
            color: #6c757d;
        }

        .category-filter {
            cursor: pointer;
        }

        .category-filter.active {
            color: #fff !important;
            background: #0d6efd !important;
            border-color: #0d6efd !important;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card shadow-lg request-card">

        
        <div class="request-header">

            <div class="d-flex align-items-center gap-3">

                <div
                    class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center"
                    style="width: 52px; height: 52px;"
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
                            <li><?php echo e($error); ?></li>
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
                            class="form-control <?php $__errorArgs = ['borrower_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('borrower_name')); ?>"
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
                            class="form-control <?php $__errorArgs = ['borrower_position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('borrower_position')); ?>"
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
                            class="form-control <?php $__errorArgs = ['borrower_department'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('borrower_department')); ?>"
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
                            class="form-control <?php $__errorArgs = ['borrower_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('borrower_phone')); ?>"
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
                            </button>

                            <?php $__currentLoopData = $assets->groupBy(fn($asset) => $asset->assetCategory->name ?? 'Lainnya'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $categoryAssets): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary category-filter"
                                    data-category="<?php echo e(Str::slug($category)); ?>"
                                >
                                    <?php echo e($category); ?>

                                </button>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>

                    </div>


                    
                    <div class="asset-list mb-4">

                        <?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php
                                $categoryName = $asset->assetCategory->name ?? 'Lainnya';
                            ?>

                            <label
                                class="asset-option d-block rounded-3 p-3 mb-2"
                                data-category="<?php echo e(Str::slug($categoryName)); ?>"
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


                
                
                

                <div class="section-title">
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
                            Silakan tanda tangan menggunakan mouse, touchpad,
                            atau layar sentuh.
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
                        class="btn btn-primary btn-lg"
                        id="submitButton"
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
    | SIGNATURE PAD
    |--------------------------------------------------------------------------
    */

    const canvas = document.getElementById('signatureCanvas');

    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)',
        minWidth: 0.8,
        maxWidth: 2.5
    });


    /*
    |--------------------------------------------------------------------------
    | RESIZE CANVAS
    |--------------------------------------------------------------------------
    */

    function resizeCanvas() {

        const ratio = Math.max(window.devicePixelRatio || 1, 1);

        const rect = canvas.getBoundingClientRect();

        canvas.width = rect.width * ratio;
        canvas.height = rect.height * ratio;

        canvas.getContext('2d').scale(ratio, ratio);

        signaturePad.clear();
    }


    resizeCanvas();

    window.addEventListener('resize', resizeCanvas);


    /*
    |--------------------------------------------------------------------------
    | CLEAR SIGNATURE
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('clearSignature')
        .addEventListener('click', function () {

            signaturePad.clear();

            document.getElementById('borrower_signature').value = '';

        });


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('loanRequestForm')
        .addEventListener('submit', function (event) {

            /*
            |--------------------------------------------------------------------------
            | Pastikan tanda tangan diisi
            |--------------------------------------------------------------------------
            */

            if (signaturePad.isEmpty()) {

                event.preventDefault();

                alert('Silakan tanda tangan terlebih dahulu.');

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Convert tanda tangan menjadi PNG Base64
            |--------------------------------------------------------------------------
            */

            const signatureData = signaturePad.toDataURL('image/png');

            document.getElementById('borrower_signature').value = signatureData;


            /*
            |--------------------------------------------------------------------------
            | Prevent double submit
            |--------------------------------------------------------------------------
            */

            const submitButton = document.getElementById('submitButton');

            submitButton.disabled = true;

            submitButton.innerHTML = `
                <span
                    class="spinner-border spinner-border-sm me-2"
                    role="status"
                ></span>
                Mengirim...
            `;

        });


    /*
    |--------------------------------------------------------------------------
    | HIGHLIGHT ASSET
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.asset-radio')
        .forEach(function (radio) {

            radio.addEventListener('change', function () {

                document
                    .querySelectorAll('.asset-option')
                    .forEach(function (option) {

                        option.classList.remove('selected');

                    });


                const parent = radio.closest('.asset-option');

                if (parent) {
                    parent.classList.add('selected');
                }

            });


            /*
            |--------------------------------------------------------------------------
            | Restore selected
            |--------------------------------------------------------------------------
            */

            if (radio.checked) {

                const parent = radio.closest('.asset-option');

                if (parent) {
                    parent.classList.add('selected');
                }

            }

        });


    /*
    |--------------------------------------------------------------------------
    | FILTER KATEGORI
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.category-filter')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const category = this.dataset.category;


                document
                    .querySelectorAll('.category-filter')
                    .forEach(function (btn) {

                        btn.classList.remove('active');

                    });


                this.classList.add('active');


                document
                    .querySelectorAll('.asset-option')
                    .forEach(function (asset) {

                        if (
                            category === 'all' ||
                            asset.dataset.category === category
                        ) {

                            asset.style.display = '';

                        } else {

                            asset.style.display = 'none';

                        }

                    });

            });

        });

});

</script>

</body>
</html><?php /**PATH /www/wwwroot/asset.adb.web.id/resources/views/loan-requests/form.blade.php ENDPATH**/ ?>