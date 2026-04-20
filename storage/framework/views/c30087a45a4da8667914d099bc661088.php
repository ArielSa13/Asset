<?php $__env->startSection('title', 'Assets'); ?>
<?php $__env->startSection('page-title', 'Asset Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Daftar Asset</h5>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('assets.pdf')); ?><?php echo e(request()->getQueryString() ? '?'.request()->getQueryString() : ''); ?>"
           class="btn btn-outline-danger btn-sm">
            <i class="bi bi-file-pdf me-1"></i>Export PDF
        </a>
        <a href="<?php echo e(route('assets.create')); ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Asset
        </a>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari nama, kode, serial number..."
                       value="<?php echo e(request('search')); ?>">
            </div>

            <div class="col-md-2">
    <select name="condition" class="form-select form-select-sm">
        <option value="">Semua Kondisi</option>
        <?php $__currentLoopData = $conditions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($val); ?>" <?php echo e(request('condition') === $val ? 'selected' : ''); ?>>
                <?php echo e($label); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>" <?php echo e(request('status') === $val ? 'selected' : ''); ?>>
                            <?php echo e($label); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="col-md-2">
                <select name="per_page" class="form-select form-select-sm">
                    <option value="10" <?php echo e(request('per_page')=='10'?'selected':''); ?>>10</option>
                    <option value="25" <?php echo e(request('per_page')=='25'?'selected':''); ?>>25</option>
                    <option value="50" <?php echo e(request('per_page')=='50'?'selected':''); ?>>50</option>
                    <option value="100" <?php echo e(request('per_page')=='100'?'selected':''); ?>>100</option>
                    <option value="all" <?php echo e(request('per_page')=='all'?'selected':''); ?>>Semua</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button class="btn btn-primary btn-sm flex-fill">Filter</button>
                <?php if(request()->hasAny(['search','status','category_id','per_page'])): ?>
                    <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama Asset</th>
                    <th>Kategori</th>
                    <th>Merek / Model</th>
                    <th>Serial Number</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Lokasi</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    
                    <td class="text-muted small">
                        <?php echo e(method_exists($assets, 'firstItem') ? $assets->firstItem() + $loop->index : $loop->iteration); ?>

                    </td>

                    <td><code class="small fw-bold"><?php echo e($asset->code); ?></code></td>

                    <td>
                        <div class="fw-semibold small"><?php echo e($asset->name); ?></div>
                        <?php if($asset->description): ?>
                            <div class="text-muted" style="font-size:.72rem">
                                <?php echo e(Str::limit($asset->description, 40)); ?>

                            </div>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if($asset->assetCategory): ?>
                            <span class="badge bg-secondary"><?php echo e($asset->assetCategory->name); ?></span>
                        <?php else: ?>
                            <span class="text-muted small">-</span>
                        <?php endif; ?>
                    </td>

                    <td class="small"><?php echo e(trim($asset->brand . ' ' . $asset->model) ?: '-'); ?></td>
                    <td class="small text-muted"><?php echo e($asset->serial_number ?: '-'); ?></td>

                    <td>
                        <span class="badge bg-<?php echo e($asset->condition_badge); ?>">
                            <?php echo e(ucfirst($asset->condition)); ?>

                        </span>
                    </td>

                    <td>
                        <span class="badge bg-<?php echo e($asset->status_badge); ?>">
                            <?php echo e(ucwords(str_replace('_',' ',$asset->status))); ?>

                        </span>
                    </td>

                    <td class="small">
                        <?php if($asset->location): ?>
                            <?php if($asset->status === 'in_use'): ?>
                                <span class="text-primary">
                                    <i class="bi bi-person-fill me-1"></i><?php echo e(Str::limit($asset->location, 25)); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-muted">
                                    <i class="bi bi-geo-alt me-1"></i><?php echo e(Str::limit($asset->location, 25)); ?>

                                </span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>

                    <td class="text-end">
                        <a href="<?php echo e(route('assets.show', $asset)); ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="<?php echo e(route('assets.edit', $asset)); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="<?php echo e(route('assets.destroy', $asset)); ?>" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus asset <?php echo e($asset->code); ?>?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="10" class="text-center text-muted py-5">
                        Belum ada asset.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($assets instanceof \Illuminate\Pagination\LengthAwarePaginator && $assets->hasPages()): ?>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between">
            <div class="text-muted small">
                Menampilkan <?php echo e($assets->firstItem()); ?> - <?php echo e($assets->lastItem()); ?>

                dari <?php echo e($assets->total()); ?>

            </div>
            <?php echo e($assets->links()); ?>

        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/arielsa/projects/project/resources/views/assets/index.blade.php ENDPATH**/ ?>