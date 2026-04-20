@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="form-header">
    <span class="eyebrow">Welcome back</span>
    <h2>Sign in to your account</h2>
    <p>Masuk ke Asset Management System.</p>
</div>

@if(session('error'))
<div class="alert-error"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
@endif
@if($errors->any())
<div class="alert-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}</div>
@endif

<form action="{{ route('login') }}" method="POST" id="loginForm">
    @csrf

    {{-- Email --}}
    <div class="field-group {{ $errors->has('email') ? 'is-invalid' : '' }}">
        <label for="email">Email Address</label>
        <div class="input-wrap">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="you@company.com"
                   autocomplete="email" autofocus required>
        </div>
        @error('email')
        <div class="invalid-msg"><i class="bi bi-x-circle-fill"></i> {{ $message }}</div>
        @enderror
    </div>

    {{-- Password --}}
    <div class="field-group {{ $errors->has('password') ? 'is-invalid' : '' }}">
        <label for="password">Password</label>
        <div class="input-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" id="passwordInput"
                   name="password"
                   placeholder="••••••••"
                   autocomplete="current-password" required>
            <button type="button" class="toggle-pw" id="toggleBtn"
                    onclick="togglePassword()" title="Tampilkan/sembunyikan password">
                <i class="bi bi-eye" id="eyeIcon"></i>
            </button>
        </div>
        @error('password')
        <div class="invalid-msg"><i class="bi bi-x-circle-fill"></i> {{ $message }}</div>
        @enderror
    </div>

    {{-- Remember me & Forgot --}}
    <div class="options-row">
        <label class="check-label">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            Ingat saya
        </label>
        <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
    </div>

    <button type="submit" class="btn-login" id="submitBtn">
        <i class="bi bi-box-arrow-in-right"></i> Sign In
    </button>
</form>

<div class="footer-note">
    &copy; {{ date('Y') }} AssetMS &mdash; Asset Management System
</div>

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<span class="spinner"></span> Signing in...';
    btn.disabled = true;
});
</script>
@endpush
@endsection
