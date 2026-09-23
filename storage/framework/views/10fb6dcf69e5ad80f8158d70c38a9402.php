<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .chart-container { position: relative; height: 220px; }
    .stat-card .stat-icon { width: 48px; height: 48px; border-radius: 12px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; flex-shrink:0; }
    .cat-bar { height: 8px; border-radius: 4px; background: #e9ecef; overflow: hidden; }
    .cat-bar-fill { height: 100%; border-radius: 4px; transition: width .6s ease; }
    .overdue-badge { animation: pulse 1.5s infinite; }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.6} }
    .summary-pill { display:inline-flex; align-items:center; gap:.4rem; padding:.3rem .75rem; border-radius:20px; font-size:.8rem; font-weight:600; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="row g-3 mb-3">
    
    <div class="col-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-pc-display"></i>
                </div>
                <div class="min-w-0">
                    <div class="fs-3 fw-bold text-primary lh-1"><?php echo e($totalAssets); ?></div>
                    <div class="text-muted small mt-1">Total Asset</div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="fs-3 fw-bold text-success lh-1"><?php echo e($availableAssets); ?></div>
                    <div class="text-muted small mt-1">Tersedia</div>
                    <div class="text-muted" style="font-size:.72rem">
                        <?php echo e($totalAssets > 0 ? round(($availableAssets/$totalAssets)*100) : 0); ?>% dari total
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100 <?php echo e($overdueLoans > 0 ? 'border-danger border-2' : ''); ?>">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <div>
                    <div class="fs-3 fw-bold text-info lh-1"><?php echo e($activeLoans); ?></div>
                    <div class="text-muted small mt-1">Dipinjam</div>
                    <?php if($overdueLoans > 0): ?>
                        <div class="overdue-badge" style="font-size:.72rem">
                            <span class="badge bg-danger"><?php echo e($overdueLoans); ?> overdue</span>
                        </div>
                    <?php else: ?>
                        <div class="text-muted" style="font-size:.72rem">Semua on-time</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-tools"></i>
                </div>
                <div>
                    <div class="fs-3 fw-bold text-warning lh-1"><?php echo e($maintenanceAssets); ?></div>
                    <div class="text-muted small mt-1">Maintenance</div>
                    <div class="text-muted" style="font-size:.72rem">
                        <?php echo e($pendingMaintenanceCount); ?> pending · <?php echo e($ongoingMaintenanceCount); ?> ongoing
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-3">
    
    <div class="col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Status Asset</h6>
                <?php $total = max($totalAssets, 1); ?>

                <?php $__currentLoopData = [
                    ['label'=>'Available',    'count'=>$availableAssets,   'color'=>'#28a745', 'bg'=>'bg-success'],
                    ['label'=>'Dipinjam',     'count'=>$inUseAssets,       'color'=>'#2563eb', 'bg'=>'bg-primary'],
                    ['label'=>'Maintenance',  'count'=>$maintenanceAssets, 'color'=>'#ffc107', 'bg'=>'bg-warning'],
                    ['label'=>'Retired',      'count'=>$retiredAssets,     'color'=>'#6c757d', 'bg'=>'bg-secondary'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-semibold"><?php echo e($s['label']); ?></span>
                        <span class="small text-muted"><?php echo e($s['count']); ?> asset (<?php echo e(round(($s['count']/$total)*100)); ?>%)</span>
                    </div>
                    <div class="cat-bar">
                        <div class="cat-bar-fill" style="width:<?php echo e(($s['count']/$total)*100); ?>%;background:<?php echo e($s['color']); ?>"></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <hr class="my-3">
                <div class="d-flex gap-2 flex-wrap">
                    <span class="summary-pill bg-success bg-opacity-10 text-success">
                        <i class="bi bi-circle-fill" style="font-size:.5rem"></i> Good: <?php echo e($goodAssets); ?>

                    </span>
                    <span class="summary-pill bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-circle-fill" style="font-size:.5rem"></i> Fair: <?php echo e($fairAssets); ?>

                    </span>
                    <span class="summary-pill bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-circle-fill" style="font-size:.5rem"></i> Poor: <?php echo e($poorAssets); ?>

                    </span>
                    <span class="summary-pill bg-dark bg-opacity-10 text-dark">
                        <i class="bi bi-circle-fill" style="font-size:.5rem"></i> Broken: <?php echo e($brokenAssets); ?>

                    </span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-2">Asset per Kategori</h6>
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card shadow-sm mb-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Ringkasan per Kategori</span>
        <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-sm btn-outline-secondary">Kelola Kategori</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle" style="font-size:.85rem">
            <thead class="table-light">
                <tr>
                    <th>Kategori</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Tersedia</th>
                    <th class="text-center">Dipinjam</th>
                    <th style="width:180px">Proporsi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $topCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $pct = $totalAssets > 0 ? ($cat->assets_count/$totalAssets)*100 : 0; ?>
                <tr>
                    <td>
                        <span class="fw-semibold"><?php echo e($cat->name); ?></span>
                        <code class="ms-1 text-muted" style="font-size:.75rem"><?php echo e($cat->prefix); ?>-xxx</code>
                    </td>
                    <td class="text-center"><strong><?php echo e($cat->assets_count); ?></strong></td>
                    <td class="text-center">
                        <span class="badge bg-success bg-opacity-75"><?php echo e($cat->available_count); ?></span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-primary bg-opacity-75"><?php echo e($cat->in_use_count); ?></span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="cat-bar flex-grow-1">
                                <div class="cat-bar-fill bg-primary" style="width:<?php echo e($pct); ?>%"></div>
                            </div>
                            <span class="text-muted" style="font-size:.75rem;width:32px"><?php echo e(round($pct)); ?>%</span>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center text-muted py-3">Belum ada kategori</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="row g-3 mb-3">
    
    <div class="col-lg-<?php echo e($overdueLoans > 0 ? '6' : '12'); ?>">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">
                    <i class="bi bi-arrow-left-right me-1 text-info"></i> Loan Aktif
                    <span class="badge bg-info ms-1"><?php echo e($activeLoans); ?></span>
                </span>
                <a href="<?php echo e(route('loans.index')); ?>" class="btn btn-sm btn-outline-info">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" style="font-size:.83rem">
                    <thead class="table-light">
                        <tr>
                            <th>Asset</th>
                            <th>Peminjam</th>
                            <th>Departemen</th>
                            <th>Dipinjam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $activeLoansDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <a href="<?php echo e(route('loans.show', $loan)); ?>" class="text-decoration-none text-dark fw-semibold">
                                    <?php echo e(Str::limit($loan->asset->name ?? '-', 25)); ?>

                                </a>
                            </td>
                            <td><?php echo e($loan->borrower_name); ?></td>
                            <td class="text-muted"><?php echo e($loan->department ?? '—'); ?></td>
                            <td class="text-muted"><?php echo e($loan->borrowed_at?->format('d M Y')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">
                            <i class="bi bi-inbox me-1"></i> Tidak ada loan aktif
                        </td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <?php if($overdueLoans > 0): ?>
    <div class="col-lg-6">
        <div class="card shadow-sm h-100 border-danger border-2">
            <div class="card-header bg-danger bg-opacity-10 d-flex justify-content-between align-items-center">
                <span class="fw-semibold text-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i> Overdue
                    <span class="badge bg-danger ms-1"><?php echo e($overdueLoans); ?></span>
                </span>
                <a href="<?php echo e(route('loans.index')); ?>" class="btn btn-sm btn-outline-danger">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" style="font-size:.83rem">
                    <thead class="table-light">
                        <tr>
                            <th>Asset</th>
                            <th>Peminjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Telat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $overdueLoansDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $days = now()->diffInDays($loan->expected_return_at); ?>
                        <tr class="table-danger">
                            <td class="fw-semibold"><?php echo e(Str::limit($loan->asset->name ?? '-', 22)); ?></td>
                            <td><?php echo e($loan->borrower_name); ?></td>
                            <td><?php echo e($loan->expected_return_at?->format('d M Y')); ?></td>
                            <td><span class="badge bg-danger"><?php echo e($days); ?> hari</span></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>


<div class="row g-3">
    
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">
                    <i class="bi bi-tools me-1 text-warning"></i> Maintenance Aktif
                    <?php if($pendingMaintenanceCount + $ongoingMaintenanceCount > 0): ?>
                    <span class="badge bg-warning text-dark ms-1"><?php echo e($pendingMaintenanceCount + $ongoingMaintenanceCount); ?></span>
                    <?php endif; ?>
                </span>
                <a href="<?php echo e(route('maintenance.index')); ?>" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" style="font-size:.83rem">
                    <thead class="table-light">
                        <tr><th>Asset</th><th>Tipe</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $pendingMaintenance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-semibold"><?php echo e(Str::limit($m->asset->name ?? '-', 25)); ?></td>
                            <td class="text-muted"><?php echo e(($m->type_label ?? ucfirst($m->type))); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($m->status_badge); ?>">
                                    <?php echo e(ucwords(str_replace('_',' ',$m->status))); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="3" class="text-center text-muted py-3">
                            <i class="bi bi-check-circle me-1 text-success"></i> Tidak ada maintenance aktif
                        </td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">
                    <i class="bi bi-plus-circle me-1 text-success"></i> Asset Terbaru
                </span>
                <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-sm btn-outline-success">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" style="font-size:.83rem">
                    <thead class="table-light">
                        <tr><th>Nama</th><th>Kode</th><th>Kategori</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentAssets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <a href="<?php echo e(route('assets.show', $asset)); ?>" class="text-decoration-none text-dark fw-semibold">
                                    <?php echo e(Str::limit($asset->name, 22)); ?>

                                </a>
                            </td>
                            <td><code style="font-size:.78rem"><?php echo e($asset->code); ?></code></td>
                            <td class="text-muted"><?php echo e($asset->assetCategory->name ?? '—'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($asset->status_badge); ?>">
                                    <?php echo e(ucwords(str_replace('_',' ',$asset->status))); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">Belum ada asset</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const catCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(catCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($assetsByCategory->pluck('name')); ?>,
        datasets: [{
            data: <?php echo json_encode($assetsByCategory->pluck('assets_count')); ?>,
            backgroundColor: [
                '#2563eb','#28a745','#ffc107','#dc3545',
                '#6f42c1','#17a2b8','#fd7e14','#20c997','#e83e8c'
            ],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'right', labels: { font: { size: 11 }, boxWidth: 12, padding: 10 } }
        },
        cutout: '60%',
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /www/wwwroot/asset.adb.web.id/resources/views/dashboard/index.blade.php ENDPATH**/ ?>