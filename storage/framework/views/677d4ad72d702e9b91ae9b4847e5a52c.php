<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Asset Management'); ?> — AssetMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --sidebar-w: 250px;
            --topbar-h: 56px;
            --navy: #1e3a5f;
            --navy2: #16294a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }

        /* ── Overlay (mobile) ── */
        #sidebarOverlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 1039;
        }

        #sidebarOverlay.show {
            display: block;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(180deg, var(--navy) 0%, var(--navy2) 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            overflow-y: auto;
            overflow-x: hidden;
            transition: transform .28s cubic-bezier(.4, 0, .2, 1);
            display: flex;
            flex-direction: column;
        }

        /* Desktop: selalu tampil */
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0) !important;
            }
        }

        /* Mobile: sembunyikan dulu */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }
        }

        .sidebar-brand {
            padding: 1.1rem 1.4rem;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-info h5 {
            color: #fff;
            margin: 0;
            font-weight: 700;
            font-size: 1rem;
        }

        .sidebar-brand .brand-info small {
            color: rgba(255, 255, 255, .45);
            font-size: .72rem;
        }

        /* Tombol X close sidebar (mobile only) */
        .sidebar-close {
            display: none;
            background: none;
            border: none;
            color: rgba(255, 255, 255, .6);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 4px;
            line-height: 1;
        }

        @media (max-width: 991px) {
            .sidebar-close {
                display: flex;
                align-items: center;
            }
        }

        .sidebar-nav {
            flex: 1;
            padding: .5rem 0 1rem;
        }

        .nav-section {
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255, 255, 255, .3);
            padding: .9rem 1.4rem .3rem;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, .68);
            padding: .58rem 1.4rem;
            font-size: .88rem;
            border-left: 3px solid transparent;
            transition: all .18s;
            display: flex;
            align-items: center;
            gap: .55rem;
        }

        .sidebar .nav-link i {
            width: 18px;
            font-size: .95rem;
            flex-shrink: 0;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, .08);
            border-left-color: rgba(77, 159, 255, .5);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, .12);
            border-left-color: #4d9fff;
            font-weight: 600;
        }

        /* ── Main ── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left .28s cubic-bezier(.4, 0, .2, 1);
        }

        .page-content {
            flex: 1 0 auto;
        }

        @media (max-width: 991px) {
            .main-wrap {
                margin-left: 0;
            }
        }

        /* ── Topbar ── */
        .topbar {
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 0 1.25rem;
            position: sticky;
            top: 0;
            z-index: 99;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: .75rem;
            min-width: 0;
        }

        /* Hamburger */
        .btn-hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #495057;
            padding: 4px 6px;
            cursor: pointer;
            border-radius: 6px;
            flex-shrink: 0;
            transition: background .15s;
        }

        .btn-hamburger:hover {
            background: #f1f3f5;
        }

        @media (max-width: 991px) {
            .btn-hamburger {
                display: flex;
                align-items: center;
            }
        }

        .topbar-title {
            font-weight: 600;
            font-size: .95rem;
            color: #212529;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: .6rem;
            flex-shrink: 0;
        }

        /* ── Page content ── */
        .page-content {
            padding: 1.25rem;
        }

        @media (max-width: 576px) {
            .page-content {
                padding: .9rem .75rem;
            }
        }

        /* ── Cards & tables ── */
        .stat-card {
            border: none;
            border-radius: 12px;
            transition: transform .2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        .table th {
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6c757d;
        }

        .badge {
            font-size: .73rem;
        }

        /* ── Pagination fix ── */
        .pagination {
            margin: 0;
            gap: 3px;
        }

        .pagination .page-link {
            font-size: .8rem;
            padding: .3rem .6rem;
            border-radius: 6px !important;
            border-color: #dee2e6;
            color: #495057;
            line-height: 1.4;
            min-width: 32px;
            text-align: center;
        }

        .pagination .page-item.active .page-link {
            background: #2563eb;
            border-color: #2563eb;
            color: #fff;
        }

        .pagination .page-item.disabled .page-link {
            color: #adb5bd;
        }

        .pagination .page-link:hover:not(.disabled) {
            background: #e9ecef;
            color: #1e3a5f;
        }

        nav[role="navigation"] {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .5rem;
        }

        nav[role="navigation"]>div:first-child {
            font-size: .8rem;
            color: #6c757d;
        }

        /* Make tables scroll on mobile */
        .table-responsive {
            -webkit-overflow-scrolling: touch;
        }

        /* ── Responsive tweaks ── */
        @media (max-width: 576px) {
            .card-body {
                padding: .85rem .9rem;
            }

            .btn {
                font-size: .82rem;
            }

            h5 {
                font-size: 1rem;
            }

            /* Header row wrap di mobile */
            .page-content .d-flex.justify-content-between {
                flex-wrap: wrap;
                gap: .5rem;
            }

            .page-content .d-flex.justify-content-between>div {
                flex-wrap: wrap;
                gap: .4rem;
            }

            /* Form filter stack di mobile */
            .card .row.g-2>[class*="col-md"] {
                width: 100% !important;
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }

            /* Badge & kode lebih kecil */
            .badge {
                font-size: .68rem;
            }

            code {
                font-size: .78rem;
            }

            /* Card stat angka */
            .stat-card .fs-2 {
                font-size: 1.5rem !important;
            }
        }

        @media (max-width: 400px) {
            .page-content {
                padding: .75rem .6rem;
            }

            .topbar {
                padding: 0 .75rem;
            }
        }

        .sidebar-logo {
            display: block;
            width: 200px;
            height: auto;
            max-height: 48px;
            object-fit: contain;
            object-position: left center;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>

    
    <div id="sidebarOverlay" onclick="closeSidebar()"></div>

    
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-info">
                <img src="<?php echo e(asset('images/logo.svg')); ?>" alt="VIVA News & Insights" class="sidebar-logo">
            </div>

            <button class="sidebar-close" onclick="closeSidebar()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="<?php echo e(route('dashboard')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>"
                        onclick="closeSidebarOnMobile()">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

                <div class="nav-section">Asset</div>
                <li class="nav-item">
                    <a href="<?php echo e(route('categories.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>"
                        onclick="closeSidebarOnMobile()">
                        <i class="bi bi-tags"></i> Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('assets.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('assets.index', 'assets.show', 'assets.create', 'assets.edit') ? 'active' : ''); ?>"
                        onclick="closeSidebarOnMobile()">
                        <i class="bi bi-pc-display"></i> Assets
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('assets.import')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('assets.import*') ? 'active' : ''); ?>"
                        onclick="closeSidebarOnMobile()">
                        <i class="bi bi-file-earmark-arrow-up"></i> Import Excel
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('loans.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('loans.*') ? 'active' : ''); ?>"
                        onclick="closeSidebarOnMobile()">
                        <i class="bi bi-arrow-left-right"></i> Loans
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('maintenance.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('maintenance.*') ? 'active' : ''); ?>"
                        onclick="closeSidebarOnMobile()">
                        <i class="bi bi-tools"></i> Maintenance
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo e(route('loan-requests.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('loan-requests.*') ? 'active' : ''); ?>"
                        onclick="closeSidebarOnMobile()">
                        <i class="bi bi-clipboard-check"></i> Request Pinjam
                        <?php $pendingReq = \App\Models\LoanRequest::where('status','pending')->count(); ?>
                        <?php if($pendingReq > 0): ?>
                            <span class="badge bg-warning text-dark ms-auto"><?php echo e($pendingReq); ?></span>
                        <?php endif; ?>
                    </a>
                </li>

                <div class="nav-section">Reports</div>
                <li class="nav-item">
                    <a href="<?php echo e(route('reports.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>"
                        onclick="closeSidebarOnMobile()">
                        <i class="bi bi-file-earmark-bar-graph"></i> Reports
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    
    <div class="main-wrap">

        
        <div class="topbar">
            <div class="topbar-left">
                <button class="btn-hamburger" onclick="openSidebar()" aria-label="Menu">
                    <i class="bi bi-list"></i>
                </button>
                <span class="topbar-title"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></span>
            </div>

            <div class="topbar-right">
                <span class="text-muted small d-none d-lg-inline">
                    <?php echo e(now()->format('d M Y, H:i')); ?> WIB
                </span>

                <?php if(auth()->guard()->check()): ?>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border d-flex align-items-center gap-2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div
                                style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#1e3a5f,#2563eb);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.75rem;font-weight:600;flex-shrink:0">
                                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                            </div>
                            <span class="small fw-semibold d-none d-sm-inline"><?php echo e(auth()->user()->name); ?></span>
                            <i class="bi bi-chevron-down small text-muted"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="min-width:200px">
                            <li>
                                <div class="px-3 py-2">
                                    <div class="fw-semibold small"><?php echo e(auth()->user()->name); ?></div>
                                    <div class="text-muted" style="font-size:.75rem"><?php echo e(auth()->user()->email); ?></div>
                                </div>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li>
                                <a href="<?php echo e(route('profile')); ?>"
                                    class="dropdown-item small <?php echo e(request()->routeIs('profile') ? 'active' : ''); ?>">
                                    <i class="bi bi-person-gear me-2"></i> Pengaturan Akun
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li>
                                <form action="<?php echo e(route('logout')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item text-danger small">
                                        <i class="bi bi-box-arrow-left me-2"></i> Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="page-content">
            <?php $__currentLoopData = ['success', 'error', 'warning', 'info']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(session($type)): ?>
                    <div class="alert alert-<?php echo e($type === 'error' ? 'danger' : $type); ?> alert-dismissible fade show"
                        role="alert">
                        <?php echo e(session($type)); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const isMobile = () => window.innerWidth < 992;

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        function closeSidebarOnMobile() {
            if (isMobile()) closeSidebar();
        }

        // Tutup sidebar saat resize ke desktop
        window.addEventListener('resize', () => {
            if (!isMobile()) {
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH /www/wwwroot/asset.adb.web.id/resources/views/layouts/app.blade.php ENDPATH**/ ?>