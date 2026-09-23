<?php $__env->startSection('title', 'Loan Detail'); ?>
<?php $__env->startSection('page-title', 'Loan Detail'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Loan #<?php echo e($loan->id); ?></h6>
                <span class="badge bg-<?php echo e($loan->status_badge); ?> fs-6"><?php echo e($loan->status_label); ?></span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Asset</small>
                        <strong><?php echo e($loan->asset->name); ?></strong>
                        <div class="text-muted small"><?php echo e($loan->asset->code); ?></div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Borrower</small>
                        <strong><?php echo e($loan->borrower_name); ?></strong>
                        <div class="text-muted small"><?php echo e($loan->borrower_department); ?></div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Phone</small>
                        <span><?php echo e($loan->borrower_phone ?: '-'); ?></span>
                    </div>
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Borrowed At</small>
                        <span><?php echo e($loan->borrowed_at->format('d M Y H:i')); ?></span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Expected Return</small>
                        <span class="<?php echo e($loan->isOverdue() ? 'text-danger fw-bold' : ''); ?>"><?php echo e($loan->expected_return_at?->format('d M Y H:i') ?? '-'); ?></span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Returned At</small>
                        <span class="text-success"><?php echo e($loan->returned_at?->format('d M Y H:i') ?? '-'); ?></span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Condition Before</small>
                        <span class="badge bg-success"><?php echo e(ucfirst($loan->condition_before)); ?></span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Condition After</small>
                        <span class="badge bg-info"><?php echo e($loan->condition_after ? ucfirst($loan->condition_after) : '-'); ?></span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Purpose</small>
                        <span><?php echo e($loan->purpose); ?></span>
                    </div>
                    <?php if($loan->notes): ?>
                    <div class="col-12">
                        <small class="text-muted d-block">Notes</small>
                        <span><?php echo e($loan->notes); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer bg-white d-flex gap-2">
                <a href="<?php echo e(route('loans.pdf', $loan)); ?>" class="btn btn-outline-info btn-sm"><i class="bi bi-file-pdf me-1"></i> Export PDF</a>
                <a href="<?php echo e(route('loans.index')); ?>" class="btn btn-outline-secondary btn-sm">Back to List</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /www/wwwroot/asset.adb.web.id/resources/views/loans/show.blade.php ENDPATH**/ ?>