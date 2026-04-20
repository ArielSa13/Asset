@extends('layouts.auth')
@section('title', 'Lupa Password')

@section('content')
<div class="form-header">
    <span class="eyebrow">Account Recovery</span>
    <h2>Lupa Password?</h2>
    <p>Masukkan email kamu dan kami akan kirimkan link untuk reset password.</p>
</div>

@if(session('status'))
<div class="alert-success">
    <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
</div>
@endif

@if($errors->any())
<div class="alert-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}</div>
@endif

<form action="{{ route('password.email') }}" method="POST" id="forgotForm">
    @csrf
    <div class="field-group {{ $errors->has('email') ? 'is-invalid' : '' }}">
        <label for="email">Email Address</label>
        <div class="input-wrap">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="you@company.com"
                   autofocus required>
        </div>
        @error('email')
        <div class="invalid-msg"><i class="bi bi-x-circle-fill"></i> {{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn-login" id="submitBtn">
        <i class="bi bi-send"></i> Kirim Link Reset
    </button>
</form>

<div class="divider">atau</div>

<div style="text-align:center">
    <a href="{{ route('login') }}" style="color:var(--blue);text-decoration:none;font-size:.875rem;font-weight:500;display:inline-flex;align-items:center;gap:.35rem;">
        <i class="bi bi-arrow-left"></i> Kembali ke halaman login
    </a>
</div>

<div class="footer-note">
    &copy; {{ date('Y') }} AssetMS &mdash; Asset Management System
</div>

@push('scripts')
<script>
document.getElementById('forgotForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<span class="spinner"></span> Mengirim...';
    btn.disabled = true;
});
</script>
@endpush
@endsection
