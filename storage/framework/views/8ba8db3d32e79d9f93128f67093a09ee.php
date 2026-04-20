<?php $__env->startSection('title', 'Edit Asset'); ?>
<?php $__env->startSection('page-title', 'Edit Asset'); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm" style="max-width:700px">
    <div class="card-header bg-white">
        <h6 class="mb-0 fw-semibold">Edit: <?php echo e($asset->name); ?></h6>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('assets.update', $asset)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="row g-3">

                
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" id="categorySelect"
                            class="form-select <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">— Pilih Kategori —</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>"
                                    data-prefix="<?php echo e($cat->prefix); ?>"
                                    <?php echo e(old('category_id', $asset->category_id) == $cat->id ? 'selected' : ''); ?>>
                                <?php echo e($cat->name); ?> (<?php echo e($cat->prefix); ?>-xxx)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">
                        Kode Aset <span class="text-danger">*</span>
                        <span id="codeSpinner" class="spinner-border spinner-border-sm text-primary ms-1 d-none"></span>
                    </label>
                    <div class="input-group">
                        <input type="text" name="code" id="codeInput"
                               class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('code', $asset->code)); ?>" required>
                        <button type="button" class="btn btn-outline-secondary"
                                onclick="refreshCode()" title="Generate ulang">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Kondisi <span class="text-danger">*</span></label>
                    <select name="condition" class="form-select" required>
                        <?php $__currentLoopData = $conditions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val); ?>" <?php echo e(old('condition', $asset->condition) === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Nama Asset <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('name', $asset->name)); ?>" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val); ?>" <?php echo e(old('status', $asset->status) === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Merek / Brand</label>
                    <input type="text" name="brand" class="form-control" value="<?php echo e(old('brand', $asset->brand)); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Model / Tipe</label>
                    <input type="text" name="model" class="form-control" value="<?php echo e(old('model', $asset->model)); ?>">
                </div>

                <div class="col-12">
                    <label class="form-label small fw-semibold">Serial Number <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="text" name="serial_number" class="form-control" value="<?php echo e(old('serial_number', $asset->serial_number)); ?>">
                </div>

                <div class="col-12">
                    <label class="form-label small fw-semibold">Deskripsi / Catatan <span class="text-muted fw-normal">(opsional)</span></label>
                    <textarea name="description" class="form-control" rows="2"><?php echo e(old('description', $asset->description)); ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-semibold">
                        <i class="bi bi-geo-alt me-1 text-secondary"></i> Lokasi Penyimpanan <span class="text-muted fw-normal">(opsional)</span>
                    </label>
                    <input type="text" name="location" class="form-control"
                           value="<?php echo e(old('location', $asset->location)); ?>"
                           placeholder="e.g. Gudang IT, Rak A2, Ruang Server, Lantai 2">
                    <div class="form-text">Lokasi fisik asset disimpan saat tidak digunakan.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Foto Asset <span class="text-muted fw-normal">(opsional)</span></label>
                    <?php if($asset->image): ?>
                        <div class="mb-2">
                            <img src="<?php echo e(Storage::url($asset->image)); ?>" class="img-thumbnail" style="max-height:70px">
                            <div class="form-text">Upload baru untuk mengganti foto</div>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

            </div>
            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Asset</button>
                <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const generateCodeUrl = '<?php echo e(route("assets.generate-code")); ?>';
const codeInput       = document.getElementById('codeInput');
const codeSpinner     = document.getElementById('codeSpinner');

// Di halaman edit, TIDAK auto-generate saat ganti kategori
// User harus klik tombol refresh secara manual jika mau kode baru
async function refreshCode() {
    const catId = document.getElementById('categorySelect').value;
    if (!catId) { alert('Pilih kategori terlebih dahulu.'); return; }

    codeSpinner.classList.remove('d-none');
    codeInput.disabled = true;
    try {
        const res  = await fetch(`${generateCodeUrl}?category_id=${catId}`);
        const data = await res.json();
        codeInput.value = data.code;
    } catch(e) { console.error(e); }
    finally {
        codeSpinner.classList.add('d-none');
        codeInput.disabled = false;
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/arielsa/projects/project/resources/views/assets/edit.blade.php ENDPATH**/ ?>