<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') — AssetMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Serif+Display:ital@0;1&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --navy: #0f2342;
            --navy2: #1a3560;
            --blue: #2563eb;
            --light: #f0f5ff;
            --white: #ffffff;
            --text: #1e293b;
            --muted: #64748b;
            --border: #dbe4f0;
            --error: #ef4444;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--light);
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* ── Left panel ── */
        .left-panel {
            width: 48%;
            background: var(--navy);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 480px;
            height: 480px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 99, 235, .35) 0%, transparent 70%);
            top: -120px;
            right: -120px;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, .2) 0%, transparent 70%);
            bottom: 60px;
            left: -80px;
        }

        .grid-overlay {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, .03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .03) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        .brand-mark {
            position: relative;
            z-index: 2;
        }

        .brand-mark .logo {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            text-decoration: none;
        }

        .brand-mark .logo-icon {
            width: 42px;
            height: 42px;
            background: var(--blue);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #fff;
        }

        .brand-mark .logo-text {
            font-family: 'DM Serif Display', serif;
            font-size: 1.4rem;
            color: #fff;
            letter-spacing: .01em;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-content h1 {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(2rem, 3vw, 2.8rem);
            color: #fff;
            line-height: 1.2;
            margin-bottom: 1.25rem;
        }

        .hero-content h1 em {
            color: #93c5fd;
            font-style: italic;
        }

        .hero-content p {
            color: rgba(255, 255, 255, .6);
            font-size: .95rem;
            line-height: 1.7;
            max-width: 360px;
        }

        .feature-list {
            position: relative;
            z-index: 2;
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: .75rem;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: rgba(255, 255, 255, .75);
            font-size: .875rem;
        }

        .feature-list li .dot {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(59, 130, 246, .25);
            border: 1px solid rgba(59, 130, 246, .4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            color: #93c5fd;
            flex-shrink: 0;
        }

        /* ── Right panel ── */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
            background: var(--white);
            position: relative;
        }

        .form-box {
            width: 100%;
            max-width: 400px;
            animation: fadeUp .45s ease both;
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-header .eyebrow {
            display: inline-block;
            font-size: .75rem;
            font-weight: 600;
            color: var(--blue);
            text-transform: uppercase;
            letter-spacing: .1em;
            background: rgba(37, 99, 235, .08);
            padding: .3rem .75rem;
            border-radius: 100px;
            margin-bottom: .9rem;
        }

        .form-header h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.9rem;
            color: var(--text);
            margin-bottom: .5rem;
            line-height: 1.2;
        }

        .form-header p {
            color: var(--muted);
            font-size: .875rem;
        }

        /* Form fields */
        .field-group {
            margin-bottom: 1.25rem;
        }

        .field-group label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: .45rem;
            letter-spacing: .02em;
        }

        /* Input wrapper — icon kiri, tombol mata kanan */
        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrap .input-icon {
            position: absolute;
            left: 14px;
            color: var(--muted);
            font-size: .95rem;
            pointer-events: none;
            z-index: 1;
            transition: color .2s;
        }

        .input-wrap input {
            width: 100%;
            padding: .78rem 2.8rem .78rem 2.65rem;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: .9rem;
            color: var(--text);
            background: var(--white);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .input-wrap input::placeholder {
            color: #a0aec0;
        }

        .input-wrap input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
        }

        .input-wrap input:focus~.input-icon {
            color: var(--blue);
        }

        /* Tombol toggle mata — di dalam kanan input */
        .toggle-pw {
            position: absolute;
            right: 0;
            height: 100%;
            width: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            font-size: 1rem;
            border-radius: 0 10px 10px 0;
            transition: color .2s, background .2s;
            z-index: 2;
        }

        .toggle-pw:hover {
            color: var(--blue);
            background: rgba(37, 99, 235, .05);
        }

        .toggle-pw:focus {
            outline: none;
        }

        .is-invalid input {
            border-color: var(--error) !important;
        }

        .invalid-msg {
            display: flex;
            align-items: center;
            gap: .35rem;
            color: var(--error);
            font-size: .78rem;
            margin-top: .4rem;
        }

        /* Options row */
        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
            font-size: .83rem;
        }

        .check-label {
            display: flex;
            align-items: center;
            gap: .45rem;
            color: var(--muted);
            cursor: pointer;
            user-select: none;
        }

        .check-label input[type=checkbox] {
            accent-color: var(--blue);
            width: 15px;
            height: 15px;
        }

        .forgot-link {
            color: var(--blue);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Button */
        .btn-login {
            width: 100%;
            padding: .85rem;
            background: var(--navy);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: .95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, transform .15s, box-shadow .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
        }

        .btn-login:hover {
            background: var(--navy2);
            box-shadow: 0 6px 24px rgba(15, 35, 66, .25);
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: .7;
            cursor: not-allowed;
            transform: none;
        }

        /* Alerts */
        .alert-error {
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            color: var(--error);
            border-radius: 10px;
            padding: .75rem 1rem;
            font-size: .85rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.25rem;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1.5px solid #bbf7d0;
            color: #166534;
            border-radius: 10px;
            padding: .75rem 1rem;
            font-size: .85rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.25rem;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: var(--muted);
            margin: 1.5rem 0;
            font-size: .78rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .footer-note {
            text-align: center;
            color: var(--muted);
            font-size: .8rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .deco-shape {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .deco-1 {
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(37, 99, 235, .06) 0%, transparent 70%);
            top: -60px;
            right: -60px;
        }

        .deco-2 {
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(37, 99, 235, .05) 0%, transparent 70%);
            bottom: 40px;
            left: -40px;
        }

        @media (max-width: 900px) {
            .left-panel {
                display: none;
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Spinner */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, .4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .6s linear infinite;
        }

        /* =========================================================
   LOGIN LOGO
   ========================================================= */

        .login-logo {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;

            width: 100% !important;
            height: auto !important;

            margin: 0 0 20px 0 !important;
            padding: 0 !important;

            overflow: hidden !important;
        }

        .login-logo img {
            display: block !important;

            /* UKURAN LOGO DIKUNCI */
            width: 300px !important;
            height: auto !important;

            /* Batas maksimal */
            max-width: 300px !important;
            max-height: 40px !important;

            /* Tidak boleh mengecil/membesar mengikuti parent */
            min-width: 0 !important;
            min-height: 0 !important;

            object-fit: contain !important;

            /* Reset positioning */
            position: static !important;
            inset: auto !important;
            transform: none !important;
        }
    </style>
</head>

<body>

    <div class="left-panel">
        <div class="grid-overlay"></div>
        <div class="brand-mark">
            <a href="#" class="logo">
                <div class="logo-icon"><i class="bi bi-boxes"></i></div>
                <span class="logo-text">Asset</span>
            </a>
        </div>
        <div class="hero-content">
            <h1>Manage Your Assets<br>with <em>Confidence</em></h1>
            <p>Track every asset, loan, and maintenance activity — all in one centralized platform designed for
                operational excellence.</p>
        </div>
        <ul class="feature-list">
            <li>
                <div class="dot"><i class="bi bi-speedometer2"></i></div> Real-time dashboard &amp; analytics
            </li>
            <li>
                <div class="dot"><i class="bi bi-arrow-left-right"></i></div> Full loan lifecycle management
            </li>
            <li>
                <div class="dot"><i class="bi bi-tools"></i></div> Maintenance tracking with PDF reports
            </li>
            <li>
                <div class="dot"><i class="bi bi-shield-check"></i></div> Secure role-based access control
            </li>
        </ul>
    </div>

    <div class="right-panel">
        <div class="deco-shape deco-1"></div>
        <div class="deco-shape deco-2"></div>
        <div class="form-box">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>

</html>
