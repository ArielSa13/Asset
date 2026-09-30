<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Terkirim — AssetMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border-radius: 16px;
            max-width: 440px;
            width: 100%;
            text-align: center;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .25);
        }

        .icon-wrap {
            width: 80px;
            height: 80px;
            background: #d1fae5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 2rem;
        }

        .request-number {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .request-number small {
            display: block;
            color: #64748b;
            margin-bottom: .25rem;
        }

        .request-number strong {
            font-size: 1.5rem;
            color: #2563eb;
            letter-spacing: .5px;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="icon-wrap">✅</div>

        <h4 class="fw-bold text-success mb-2">Permintaan Terkirim!</h4>

        <p class="text-muted mb-4">
            Permintaan peminjaman kamu sudah diterima dan sedang menunggu persetujuan dari tim IT Support.
            Kamu akan dihubungi setelah diproses.
        </p>

        <?php if(session('request_number')): ?>
            <div class="request-number">
                <small>Nomor Request</small>
                <strong><?php echo e(session('request_number')); ?></strong>
                <div class="small text-muted mt-1">
                    Simpan nomor ini untuk melacak status request kamu.
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="<?php echo e(route('loan-requests.track', ['request_number' => session('request_number')])); ?>"
                    class="btn btn-primary">
                    <i class="bi bi-search me-1"></i> Lacak Request
                </a>
                <a href="<?php echo e(route('loan-requests.public.form')); ?>" class="btn btn-outline-secondary">
                    Ajukan Peminjaman Lain
                </a>
            </div>
        <?php else: ?>
            <a href="<?php echo e(route('loan-requests.public.form')); ?>" class="btn btn-primary">
                Ajukan Peminjaman Lain
            </a>
        <?php endif; ?>
    </div>
</body>

</html>
<?php /**PATH /www/wwwroot/asset.adb.web.id/resources/views/loan-requests/success.blade.php ENDPATH**/ ?>