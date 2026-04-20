<?php $__env->startSection('title', $asset->name); ?>
<?php $__env->startSection('page-title', 'Asset Detail'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <?php if($asset->image): ?>
                    <img src="<?php echo e(Storage::url($asset->image)); ?>" alt="<?php echo e($asset->name); ?>" class="img-fluid rounded mb-3" style="max-height:200px">
                <?php else: ?>
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height:150px">
                        <i class="bi bi-image text-muted fs-1"></i>
                    </div>
                <?php endif; ?>
                <h5 class="fw-bold"><?php echo e($asset->name); ?></h5>
                <code class="text-muted"><?php echo e($asset->code); ?></code>
                <div class="mt-2">
                    <span class="badge bg-<?php echo e($asset->status_badge); ?> me-1"><?php echo e(ucwords(str_replace('_',' ',$asset->status))); ?></span>
                    <span class="badge bg-<?php echo e($asset->condition_badge); ?>"><?php echo e(ucfirst($asset->condition)); ?></span>
                </div>
            </div>
            <ul class="list-group list-group-flush small">
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Category</span><strong><?php echo e($asset->category); ?></strong></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Brand</span><strong><?php echo e($asset->brand ?: '-'); ?></strong></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Model</span><strong><?php echo e($asset->model ?: '-'); ?></strong></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Serial No.</span><strong><?php echo e($asset->serial_number ?: '-'); ?></strong></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Location</span><strong><?php echo e($asset->location ?: '-'); ?></strong></li>
                <?php if($asset->purchase_date): ?>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Purchase Date</span><strong><?php echo e($asset->purchase_date->format('d M Y')); ?></strong></li>
                <?php endif; ?>
                <?php if($asset->purchase_price): ?>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Price</span><strong>Rp <?php echo e(number_format($asset->purchase_price, 0, ',', '.')); ?></strong></li>
                <?php endif; ?>
            </ul>
            <div class="card-footer bg-white d-flex gap-2">
                <a href="<?php echo e(route('assets.edit', $asset)); ?>" class="btn btn-primary btn-sm flex-fill"><i class="bi bi-pencil me-1"></i>Edit</a>
                <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline-secondary btn-sm flex-fill">Back</a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Loan History</span>
                <?php if($asset->status === 'available'): ?>
                <a href="<?php echo e(route('loans.create')); ?>?asset_id=<?php echo e($asset->id); ?>" class="btn btn-sm btn-outline-primary">+ New Loan</a>
                <?php endif; ?>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light"><tr><th>Borrower</th><th>Borrowed</th><th>Returned</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $asset->loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="small"><?php echo e($loan->borrower_name); ?><br><span class="text-muted" style="font-size:.75rem"><?php echo e($loan->borrower_department); ?></span></td>
                            <td class="small"><?php echo e($loan->borrowed_at->format('d M Y')); ?></td>
                            <td class="small"><?php echo e($loan->returned_at?->format('d M Y') ?? '-'); ?></td>
                            <td><span class="badge bg-<?php echo e($loan->status_badge); ?>"><?php echo e($loan->status_label); ?></span></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">No loan history</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Maintenance History</span>
                <a href="<?php echo e(route('maintenance.create')); ?>?asset_id=<?php echo e($asset->id); ?>" class="btn btn-sm btn-outline-warning">+ Log Maintenance</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light"><tr><th>Type</th><th>Description</th><th>Vendor</th><th>Cost</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $asset->maintenances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="small"><?php echo e(($m->type_label ?? ucfirst($m->type))); ?></td>
                            <td class="small"><?php echo e(Str::limit($m->description, 40)); ?></td>
                            <td class="small"><?php echo e($m->vendor_name ?: '-'); ?></td>
                            <td class="small"><?php echo e($m->cost ? 'Rp '.number_format($m->cost,0,',','.') : '-'); ?></td>
                            <td><span class="badge bg-<?php echo e($m->status_badge); ?>"><?php echo e(ucwords(str_replace('_',' ',$m->status))); ?></span></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="text-center text-muted py-3">No maintenance records</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/arielsa/projects/project/resources/views/assets/show.blade.php ENDPATH**/ ?>