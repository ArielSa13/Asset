<?php $__env->startSection('title', 'Loans'); ?>
<?php $__env->startSection('page-title', 'Loan Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Semua Peminjaman</h5>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('loans.export-pdf')); ?><?php echo e(request()->getQueryString() ? '?'.request()->getQueryString() : ''); ?>"
           class="btn btn-outline-danger btn-sm">
            <i class="bi bi-file-pdf me-1"></i>Export PDF
        </a>
        <a href="<?php echo e(route('loans.create')); ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> New Loan
        </a>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search borrower, department, asset..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="overdue" <?php echo e(request('status') === 'overdue' ? 'selected' : ''); ?>>Overdue</option>
                    <option value="returned" <?php echo e(request('status') === 'returned' ? 'selected' : ''); ?>>Returned</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary btn-sm w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Asset</th>
                    <th>Borrower</th>
                    <th>Department</th>
                    <th>Borrowed</th>
                    <th>Expected Return</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="small fw-semibold"><?php echo e($loan->asset->name ?? '-'); ?></td>
                    <td class="small"><?php echo e($loan->borrower_name); ?></td>
                    <td class="small text-muted"><?php echo e($loan->borrower_department); ?></td>
                    <td class="small"><?php echo e($loan->borrowed_at->format('d M Y')); ?></td>
                    <td class="small">
                        <?php echo e($loan->expected_return_at?->format('d M Y') ?? '-'); ?>

                        <?php if($loan->isOverdue()): ?><br><span class="text-danger small">Overdue!</span><?php endif; ?>
                    </td>
                    <td><span class="badge bg-<?php echo e($loan->status_badge); ?>"><?php echo e($loan->status_label); ?></span></td>
                    <td class="text-end">
                        <a href="<?php echo e(route('loans.show', $loan)); ?>" class="btn btn-sm btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>
                        <?php if(!$loan->isReturned()): ?>
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#returnModal<?php echo e($loan->id); ?>" title="Return Asset">
                            <i class="bi bi-arrow-return-left"></i>
                        </button>
                        <a href="<?php echo e(route('loans.pdf', $loan)); ?>" class="btn btn-sm btn-outline-info" title="Export PDF"><i class="bi bi-file-pdf"></i></a>
                        <?php else: ?>
                        <a href="<?php echo e(route('loans.pdf', $loan)); ?>" class="btn btn-sm btn-outline-info" title="Export PDF"><i class="bi bi-file-pdf"></i></a>
                        <?php endif; ?>
                    </td>
                </tr>

                
                <?php if(!$loan->isReturned()): ?>
                <div class="modal fade" id="returnModal<?php echo e($loan->id); ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="<?php echo e(route('loans.return', $loan)); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <div class="modal-header">
                                    <h6 class="modal-title fw-semibold">Return Asset: <?php echo e($loan->asset->name); ?></h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Condition After Return <span class="text-danger">*</span></label>
                                        <select name="condition_after" class="form-select" required>
                                            <option value="good">Good</option>
                                            <option value="fair">Fair</option>
                                            <option value="poor">Poor</option>
                                            <option value="broken">Broken</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label small fw-semibold">Notes</label>
                                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional return notes..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success btn-sm">Confirm Return</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        No loan records found.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($loans->hasPages()): ?>
    <div class="card-footer bg-white"><?php echo e($loans->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /www/wwwroot/asset.adb.web.id/resources/views/loans/index.blade.php ENDPATH**/ ?>