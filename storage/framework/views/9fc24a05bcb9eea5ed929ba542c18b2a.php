<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daftar Asset</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 16px; }
        .header { text-align: center; border-bottom: 2px solid #1e3a5f; padding-bottom: 10px; margin-bottom: 16px; }
        .header h2 { color: #1e3a5f; margin: 0 0 3px; font-size: 17px; }
        .header p  { margin: 0; color: #666; font-size: 10px; }
        .doc-title { text-align: center; font-size: 13px; font-weight: bold; margin: 12px 0; text-transform: uppercase; letter-spacing: .05em; }
        .meta { font-size: 10px; color: #666; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        thead th { background: #1e3a5f; color: #fff; padding: 6px 7px; font-size: 10px; text-align: left; }
        tbody td { padding: 5px 7px; border-bottom: 1px solid #eee; font-size: 10px; vertical-align: top; }
        tbody tr:nth-child(even) td { background: #f8f9fa; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .b-success  { background: #d4edda; color: #155724; }
        .b-primary  { background: #cce5ff; color: #004085; }
        .b-warning  { background: #fff3cd; color: #856404; }
        .b-secondary{ background: #e2e3e5; color: #383d41; }
        .b-danger   { background: #f8d7da; color: #721c24; }
        .b-dark     { background: #d6d8d9; color: #1b1e21; }
        .summary { background: #eef2ff; border: 1px solid #c7d2fe; padding: 10px 14px; border-radius: 6px; margin-top: 14px; font-size: 10px; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Asset Management System</h2>
        <p>Laporan Daftar Aset Perusahaan</p>
    </div>
    <div class="doc-title">Daftar Aset</div>
    <div class="meta">
        Tanggal Cetak: <?php echo e($now->translatedFormat('d F Y, H:i')); ?> WIB &nbsp;|&nbsp; Total: <?php echo e($assets->count()); ?> aset
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kode</th>
                <th>Nama Asset</th>
                <th>Merek / Model</th>
                <th>Kondisi</th>
                <th>Status</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($i + 1); ?></td>
                <td><strong><?php echo e($a->code); ?></strong></td>
                <td><?php echo e($a->name); ?></td>
                <td><?php echo e(trim($a->brand . ' ' . $a->model) ?: '-'); ?></td>
                <td>
                    <?php
                        $cb = ['good'=>'b-success','fair'=>'b-warning','poor'=>'b-danger','broken'=>'b-dark'];
                    ?>
                    <span class="badge <?php echo e($cb[$a->condition] ?? 'b-secondary'); ?>"><?php echo e(ucfirst($a->condition)); ?></span>
                </td>
                <td>
                    <?php
                        $sb = ['available'=>'b-success','in_use'=>'b-primary','maintenance'=>'b-warning','retired'=>'b-secondary'];
                    ?>
                    <span class="badge <?php echo e($sb[$a->status] ?? 'b-secondary'); ?>"><?php echo e(ucwords(str_replace('_',' ',$a->status))); ?></span>
                </td>
                <td><?php echo e($a->location ?: '-'); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="9" style="text-align:center;color:#999;padding:16px">Tidak ada data aset</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php
        $byStatus   = $assets->groupBy('status');
    ?>
    <div class="summary">
        <strong>Ringkasan</strong><br><br>
        Total Aset: <?php echo e($assets->count()); ?> &nbsp;|&nbsp;
        Available: <?php echo e($byStatus->get('available',collect())->count()); ?> &nbsp;|&nbsp;
        In Use: <?php echo e($byStatus->get('in_use',collect())->count()); ?> &nbsp;|&nbsp;
        Maintenance: <?php echo e($byStatus->get('maintenance',collect())->count()); ?> &nbsp;|&nbsp;
        Retired: <?php echo e($byStatus->get('retired',collect())->count()); ?><br>
    </div>

    <div class="footer">
        Dicetak oleh Asset Management System &mdash; <?php echo e($now->format('d M Y H:i')); ?> WIB
    </div>
</body>
</html>
<?php /**PATH /www/wwwroot/asset.adb.web.id/resources/views/assets/pdf.blade.php ENDPATH**/ ?>