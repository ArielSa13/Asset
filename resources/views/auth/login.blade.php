@extends('layouts.auth')

@section('title', 'Login')

@section('content')


    {{-- ========================================= --}}
    {{-- LOGO --}}
    {{-- ========================================= --}}

    <div class="login-logo">
        <img src="{{ asset('images/logo.svg') }}" alt="AssetMS Logo">
    </div>


    {{-- ========================================= --}}
    {{-- HEADER --}}
    {{-- ========================================= --}}

    <div class="form-header">
        <p>
            Masuk ke Asset Management System.
        </p>
    </div>


    {{-- ========================================= --}}
    {{-- SESSION ERROR --}}
    {{-- ========================================= --}}

    @if (session('error'))
        <div class="alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
        </div>
    @endif


    {{-- ========================================= --}}
    {{-- VALIDATION ERROR --}}
    {{-- ========================================= --}}

    @if ($errors->any())
        <div class="alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first() }}
        </div>
    @endif


    {{-- ========================================= --}}
    {{-- LOGIN FORM --}}
    {{-- ========================================= --}}

    <form action="{{ route('login') }}" method="POST" id="loginForm">

        @csrf


        {{-- EMAIL --}}

        <div class="field-group {{ $errors->has('email') ? 'is-invalid' : '' }}">

            <label for="email">
                Email Address
            </label>

            <div class="input-wrap">

                <i class="bi bi-envelope input-icon"></i>

                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    placeholder="you@company.com" autocomplete="email" autofocus required>

            </div>

            @error('email')
                <div class="invalid-msg">

                    <i class="bi bi-x-circle-fill"></i>

                    {{ $message }}

                </div>
            @enderror

        </div>


        {{-- PASSWORD --}}

        <div class="field-group {{ $errors->has('password') ? 'is-invalid' : '' }}">

            <label for="password">
                Password
            </label>

            <div class="input-wrap">

                <i class="bi bi-lock input-icon"></i>

                <input type="password" id="passwordInput" name="password" placeholder="••••••••"
                    autocomplete="current-password" required>

                <button type="button" class="toggle-pw" id="toggleBtn" onclick="togglePassword()"
                    title="Tampilkan/sembunyikan password">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>

            </div>

            @error('password')
                <div class="invalid-msg">

                    <i class="bi bi-x-circle-fill"></i>

                    {{ $message }}

                </div>
            @enderror

        </div>


        {{-- ========================================= --}}
        {{-- REMEMBER & FORGOT PASSWORD --}}
        {{-- ========================================= --}}

        <div class="options-row">

            <label class="check-label">

                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>

                Ingat saya

            </label>


            <a href="{{ route('password.request') }}" class="forgot-link">
                Lupa password?
            </a>

        </div>


        {{-- ========================================= --}}
        {{-- LOGIN BUTTON --}}
        {{-- ========================================= --}}

        <button type="submit" class="btn-login" id="submitBtn">

            <i class="bi bi-box-arrow-in-right"></i>

            Sign In

        </button>

    </form>


    {{-- ========================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================= --}}

    <div class="footer-note">

        &copy; {{ date('Y') }}

        AssetMS

        &mdash;

        Asset Management System

    </div>


    {{-- ========================================= --}}
    {{-- CUSTOM STYLE --}}
    {{-- ========================================= --}}


    @push('styles')
        <style>
            /* =========================================================
                                                       LOGIN LOGO
                                                       Ukuran dikunci agar tidak mengikuti ukuran container
                                                       ========================================================= */

            .login-logo {
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                width: 100% !important;
                height: auto !important;
                margin: 0 0 20px 0 !important;
                padding: 0 !important;
            }

            .login-logo img {
                display: block !important;

                /* UKURAN LOGO DIKUNCI */
                width: 160px !important;
                height: auto !important;

                /* Batas maksimal */
                max-width: 160px !important;
                max-height: 40px !important;

                /* Jangan pernah memenuhi container */
                min-width: 0 !important;
                min-height: 0 !important;

                object-fit: contain !important;

                /* Reset kemungkinan CSS dari layout */
                position: static !important;
                transform: none !important;
            }
        </style>
    @endpush



    {{-- ========================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================= --}}

    @push('scripts')
        <script>
            /*
                                                                                |--------------------------------------------------------------------------
                                                                                | TOGGLE PASSWORD
                                                                                |--------------------------------------------------------------------------
                                                                                */

            function togglePassword() {

                const input = document.getElementById('passwordInput');
                const icon = document.getElementById('eyeIcon');

                if (input.type === 'password') {

                    input.type = 'text';

                    icon.className = 'bi bi-eye-slash';

                } else {

                    input.type = 'password';

                    icon.className = 'bi bi-eye';

                }

            }


            /*
            |--------------------------------------------------------------------------
            | PREVENT DOUBLE SUBMIT
            |--------------------------------------------------------------------------
            */

            document
                .getElementById('loginForm')
                .addEventListener('submit', function() {

                    const btn = document.getElementById('submitBtn');

                    btn.innerHTML = `
                    <span
                        class="spinner"
                        aria-hidden="true"
                    ></span>

                    Signing in...
                `;

                    btn.disabled = true;

                });
        </script>
    @endpush


@endsection
