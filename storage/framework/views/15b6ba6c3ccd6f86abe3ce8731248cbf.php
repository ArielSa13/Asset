<?php $__env->startSection('title', 'Kategori Asset'); ?>
<?php $__env->startSection('page-title', 'Kategori Asset'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Kelola Kategori</h5>
    <a href="<?php echo e(route('categories.create')); ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama Kategori</th>
                    <th>Prefix Kode</th>
                    <th>Contoh Kode</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Asset</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-muted small"><?php echo e($loop->iteration); ?></td>
                    <td class="fw-semibold"><?php echo e($cat->name); ?></td>
                    <td>
                        <span class="badge bg-dark fs-6 font-monospace"><?php echo e($cat->prefix); ?>-</span>
                    </td>
                    <td class="text-muted small font-monospace">
                        <?php echo e($cat->prefix); ?>-001, <?php echo e($cat->prefix); ?>-002...
                    </td>
                    <td class="small text-muted"><?php echo e($cat->description ?: '-'); ?></td>
                    <td>
                        <span class="badge bg-<?php echo e($cat->assets_count > 0 ? 'primary' : 'secondary'); ?>">
                            <?php echo e($cat->assets_count); ?> asset
                        </span>
                    </td>
                    <td>
                        <?php if($cat->is_active): ?>
                            <span class="badge bg-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Non-aktif</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="<?php echo e(route('categories.edit', $cat)); ?>"
                           class="btn btn-sm btn-outline-primary" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <?php if($cat->assets_count === 0): ?>
                        <form action="<?php echo e(route('categories.destroy', $cat)); ?>" method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Hapus kategori <?php echo e($cat->name); ?>?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        <?php else: ?>
                        <button class="btn btn-sm btn-outline-secondary"
                                disabled title="Tidak bisa dihapus — masih ada asset">
                            <i class="bi bi-trash"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-tag fs-2 d-block mb-2"></i>
                        Belum ada kategori.
                        <a href="<?php echo e(route('categories.create')); ?>">Tambah sekarang</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($categories->hasPages()): ?>
    <div class="card-footer bg-white"><?php echo e($categories->links()); ?></div>
    <?php endif; ?>
</div>


<div class="alert alert-info border-0 mt-3 small">
    <i class="bi bi-info-circle me-1"></i>
    <strong>Tips:</strong> Prefix kode menentukan awalan nomor asset.
    Contoh: kategori <strong>Monitor</strong> dengan prefix <strong>MON</strong>
    akan menghasilkan kode <strong>MON-001</strong>, <strong>MON-002</strong>, dst.
    Kategori yang sudah memiliki asset <strong>tidak bisa dihapus</strong>,
    tapi bisa di-nonaktifkan agar tidak muncul di form tambah asset.
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /www/wwwroot/asset.adb.web.id/resources/views/categories/index.blade.php ENDPATH**/ ?>