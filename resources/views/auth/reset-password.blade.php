@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
<div class="form-header">
    <span class="eyebrow">Password Baru</span>
    <h2>Buat Password Baru</h2>
    <p>Password baru minimal 8 karakter.</p>
</div>

@if($errors->any())
<div class="alert-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}</div>
@endif

<form action="{{ route('password.store') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    {{-- Email --}}
    <div class="field-group {{ $errors->has('email') ? 'is-invalid' : '' }}">
        <label for="email">Email Address</label>
        <div class="input-wrap">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" id="email" name="email"
                   value="{{ old('email', $request->email) }}"
                   placeholder="you@company.com" required>
        </div>
        @error('email')
        <div class="invalid-msg"><i class="bi bi-x-circle-fill"></i> {{ $message }}</div>
        @enderror
    </div>

    {{-- Password baru --}}
    <div class="field-group {{ $errors->has('password') ? 'is-invalid' : '' }}">
        <label for="password">Password Baru</label>
        <div class="input-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" id="passwordInput" name="password"
                   placeholder="Min. 8 karakter" required>
            <button type="button" class="toggle-pw" onclick="togglePw('passwordInput','eye1')" title="Tampilkan password">
                <i class="bi bi-eye" id="eye1"></i>
            </button>
        </div>
        @error('password')
        <div class="invalid-msg"><i class="bi bi-x-circle-fill"></i> {{ $message }}</div>
        @enderror
    </div>

    {{-- Konfirmasi --}}
    <div class="field-group">
        <label for="password_confirmation">Konfirmasi Password</label>
        <div class="input-wrap">
            <i class="bi bi-lock-fill input-icon"></i>
            <input type="password" id="confirmInput" name="password_confirmation"
                   placeholder="Ulangi password baru" required>
            <button type="button" class="toggle-pw" onclick="togglePw('confirmInput','eye2')" title="Tampilkan password">
                <i class="bi bi-eye" id="eye2"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn-login" style="margin-top:.5rem">
        <i class="bi bi-shield-check"></i> Reset Password
    </button>
</form>

<div class="footer-note">
    &copy; {{ date('Y') }} AssetMS &mdash; Asset Management System
</div>

@push('scripts')
<script>
function togglePw(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endpush
@endsection
