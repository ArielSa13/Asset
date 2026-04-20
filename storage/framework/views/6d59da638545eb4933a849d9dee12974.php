<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman Asset — AssetMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); min-height: 100vh; display:flex; align-items:center; justify-content:center; padding: 1.5rem; }
        .form-card { background: #fff; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,.25); max-width: 560px; width: 100%; }
        .form-header { background: linear-gradient(135deg, #1e3a5f, #2563eb); border-radius: 16px 16px 0 0; padding: 1.75rem; color: #fff; text-align: center; }
        .form-header h4 { margin: 0; font-weight: 700; }
        .form-header p { margin: .3rem 0 0; opacity: .8; font-size: .9rem; }
        .form-body { padding: 1.75rem; }
        .asset-option { border: 2px solid #e9ecef; border-radius: 10px; padding: .75rem 1rem; cursor: pointer; transition: all .2s; margin-bottom: .5rem; }
        .asset-option:hover { border-color: #2563eb; background: #f0f4ff; }
        .asset-option input[type=radio] { accent-color: #2563eb; }
        .asset-option.selected { border-color: #2563eb; background: #f0f4ff; }
        .badge-available { background: #d1fae5; color: #065f46; font-size: .72rem; padding: .2rem .5rem; border-radius: 20px; }
    </style>
</head>
<body>
<div class="form-card">
    <div class="form-header">
        <div style="font-size:2rem; margin-bottom:.5rem">📋</div>
        <h4>Form Request Peminjaman Asset</h4>
        <p>Isi form ini untuk mengajukan peminjaman perangkat IT</p>
    </div>
    <div class="form-body">
        <?php if($errors->any()): ?>
        <div class="alert alert-danger py-2 px-3 mb-3 small">
            <ul class="mb-0 ps-3">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form action="<?php echo e(route('loan-requests.public.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            
            <h6 class="fw-semibold text-muted mb-3" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.05em">
                <i class="bi bi-person me-1"></i> Data Peminjam
            </h6>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="borrower_name" class="form-control <?php $__errorArgs = ['borrower_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('borrower_name')); ?>" placeholder="Nama lengkap kamu" required>
                <?php $__errorArgs = ['borrower_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label small fw-semibold">Departemen</label>
                    <input type="text" name="borrower_department" class="form-control"
                           value="<?php echo e(old('borrower_department')); ?>" placeholder="e.g. HRD, Finance, Marketing">
                </div>
                <div class="col-sm-6">
                    <label class="form-label small fw-semibold">No. HP / WhatsApp</label>
                    <input type="text" name="borrower_phone" class="form-control"
                           value="<?php echo e(old('borrower_phone')); ?>" placeholder="08xx-xxxx-xxxx">
                </div>
            </div>

            <hr class="my-3">

            
            <h6 class="fw-semibold text-muted mb-3" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.05em">
                <i class="bi bi-pc-display me-1"></i> Pilih Asset
            </h6>

            <?php if($assets->isEmpty()): ?>
                <div class="alert alert-warning text-center py-3">
                    <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                    Tidak ada asset yang tersedia saat ini.
                </div>
            <?php else: ?>
                <?php $__errorArgs = ['asset_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="alert alert-danger py-2 px-3 small mb-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                
                <div class="mb-2 d-flex gap-2 flex-wrap" id="catFilters">
                    <button type="button" class="btn btn-sm btn-primary cat-filter active" data-cat="all">Semua</button>
                    <?php $__currentLoopData = $assets->groupBy(fn($a) => $a->assetCategory->name ?? 'Lainnya'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catName => $catAssets): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" class="btn btn-sm btn-outline-secondary cat-filter" data-cat="<?php echo e($catName); ?>">
                        <?php echo e($catName); ?> <span class="badge bg-secondary ms-1"><?php echo e($catAssets->count()); ?></span>
                    </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div style="max-height:280px;overflow-y:auto" class="pe-1" id="assetList">
                    <?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="asset-wrap" data-cat="<?php echo e($asset->assetCategory->name ?? 'Lainnya'); ?>">
                    <label class="asset-option d-flex align-items-center gap-3 <?php echo e(old('asset_id') == $asset->id ? 'selected' : ''); ?>">
                        <input type="radio" name="asset_id" value="<?php echo e($asset->id); ?>"
                               <?php echo e(old('asset_id') == $asset->id ? 'checked' : ''); ?> required>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold small"><?php echo e($asset->name); ?></div>
                            <div class="text-muted" style="font-size:.75rem">
                                <code><?php echo e($asset->code); ?></code>
                                <?php if($asset->brand): ?> · <?php echo e($asset->brand); ?><?php endif; ?>
                                <?php if($asset->model): ?> <?php echo e($asset->model); ?><?php endif; ?>
                            </div>
                        </div>
                        <span class="badge-available">Tersedia</span>
                    </label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <hr class="my-3">

            
            <h6 class="fw-semibold text-muted mb-3" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.05em">
                <i class="bi bi-chat-text me-1"></i> Keperluan
            </h6>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Keperluan Peminjaman <span class="text-danger">*</span></label>
                <input type="text" name="purpose" class="form-control <?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('purpose')); ?>" placeholder="e.g. Presentasi ke klien, WFH, Training" required>
                <?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-semibold">Catatan Tambahan <span class="text-muted fw-normal">(opsional)</span></label>
                <textarea name="notes" class="form-control" rows="2"
                          placeholder="Informasi tambahan yang perlu diketahui IT Support"><?php echo e(old('notes')); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" <?php if($assets->isEmpty()): ?> disabled <?php endif; ?>>
                <i class="bi bi-send me-2"></i> Kirim Permintaan
            </button>
        </form>

        <div class="text-center mt-3 text-muted" style="font-size:.75rem">
            Permintaan akan diproses oleh tim IT Support. Kamu akan dihubungi setelah disetujui.
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Highlight asset yang dipilih
document.querySelectorAll('.asset-option input[type=radio]').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('.asset-option').forEach(el => el.classList.remove('selected'));
        this.closest('.asset-option').classList.add('selected');
    });
});

// Filter kategori
document.querySelectorAll('.cat-filter').forEach(btn => {
    btn.addEventListener('click', function () {
        // Reset semua tombol
        document.querySelectorAll('.cat-filter').forEach(b => {
            b.classList.remove('btn-primary');
            b.classList.add('btn-outline-secondary');
        });
        // Aktifkan tombol yang diklik
        this.classList.add('btn-primary');
        this.classList.remove('btn-outline-secondary');

        const cat = this.dataset.cat;
        document.querySelectorAll('.asset-wrap').forEach(wrap => {
            if (cat === 'all' || wrap.dataset.cat === cat) {
                wrap.style.display = '';
            } else {
                wrap.style.display = 'none';
            }
        });
    });
});
</script>
</body>
</html>
<?php /**PATH /home/arielsa/projects/project/resources/views/loan-requests/form.blade.php ENDPATH**/ ?>