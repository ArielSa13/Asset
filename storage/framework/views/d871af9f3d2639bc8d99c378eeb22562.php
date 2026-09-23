<?php $__env->startSection('title', 'Maintenance'); ?>
<?php $__env->startSection('page-title', 'Maintenance Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Maintenance Records</h5>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('maintenance.pdf')); ?>" class="btn btn-outline-danger btn-sm"><i class="bi bi-file-pdf me-1"></i>Export PDF</a>
        <a href="<?php echo e(route('maintenance.create')); ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Log Maintenance</a>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search asset, vendor, description..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($val); ?>" <?php echo e(request('status') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($val); ?>" <?php echo e(request('type') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="alert alert-light border d-flex align-items-center gap-2 py-2">
    <i class="bi bi-cash-coin text-success"></i>
    <span class="small">Total Maintenance Cost (Completed): <strong>Rp <?php echo e(number_format($totalCost, 0, ',', '.')); ?></strong></span>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Asset</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Vendor</th>
                    <th>Cost</th>
                    <th>Started</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $maintenances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="small fw-semibold"><?php echo e($m->asset->name ?? '-'); ?></td>
                    <td><span class="badge bg-secondary"><?php echo e(($m->type_label ?? ucfirst($m->type))); ?></span></td>
                    <td class="small"><?php echo e(Str::limit($m->description, 40)); ?></td>
                    <td class="small text-muted"><?php echo e($m->vendor_name ?: '-'); ?></td>
                    <td class="small"><?php echo e($m->cost ? 'Rp '.number_format($m->cost,0,',','.') : '-'); ?></td>
                    <td class="small"><?php echo e($m->started_at->format('d M Y')); ?></td>
                    <td><span class="badge bg-<?php echo e($m->status_badge); ?>"><?php echo e(ucwords(str_replace('_',' ',$m->status))); ?></span></td>
                    <td class="text-end">
                        <a href="<?php echo e(route('maintenance.show', $m)); ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                        <a href="<?php echo e(route('maintenance.edit', $m)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form action="<?php echo e(route('maintenance.destroy', $m)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="bi bi-tools fs-3 d-block mb-2"></i>
                        No maintenance records found.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($maintenances->hasPages()): ?>
    <div class="card-footer bg-white"><?php echo e($maintenances->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /www/wwwroot/asset.adb.web.id/resources/views/maintenance/index.blade.php ENDPATH**/ ?>