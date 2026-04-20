@extends('layouts.app')
@section('title', 'Profil Akun')
@section('page-title', 'Profil Akun')

@section('content')
<div class="row g-4" style="max-width:860px">

    {{-- Kartu info akun --}}
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#1e3a5f,#2563eb);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <div class="text-muted small">{{ auth()->user()->email }}</div>
                    <div class="text-muted" style="font-size:.72rem">
                        Bergabung sejak {{ auth()->user()->created_at->translatedFormat('d F Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Ubah Email --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(37,99,235,.1);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-envelope text-primary" style="font-size:.9rem"></i>
                </div>
                <h6 class="mb-0 fw-semibold">Ubah Email</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update-email') }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Saat Ini</label>
                        <input type="text" class="form-control form-control-sm bg-light"
                               value="{{ auth()->user()->email }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">
                            Email Baru <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email"
                               class="form-control form-control-sm @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="email@baru.com" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">
                            Konfirmasi dengan Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="password" name="current_password" id="pwEmail"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="Password kamu saat ini" required>
                            <button type="button" class="btn btn-outline-secondary"
                                    onclick="toggleField('pwEmail','eyeEmail')">
                                <i class="bi bi-eye" id="eyeEmail"></i>
                            </button>
                        </div>
                        @error('current_password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-check-lg me-1"></i> Simpan Email Baru
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Form Ubah Password --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(37,99,235,.1);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-lock text-primary" style="font-size:.9rem"></i>
                </div>
                <h6 class="mb-0 fw-semibold">Ubah Password</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update-password') }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">
                            Password Saat Ini <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="password" name="current_password" id="pwCurrent"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="Password lama" required>
                            <button type="button" class="btn btn-outline-secondary"
                                    onclick="toggleField('pwCurrent','eyeCurrent')">
                                <i class="bi bi-eye" id="eyeCurrent"></i>
                            </button>
                        </div>
                        @error('current_password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">
                            Password Baru <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="password" name="password" id="pwNew"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min. 8 karakter" required>
                            <button type="button" class="btn btn-outline-secondary"
                                    onclick="toggleField('pwNew','eyeNew')">
                                <i class="bi bi-eye" id="eyeNew"></i>
                            </button>
                        </div>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">
                            Konfirmasi Password Baru <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="password" name="password_confirmation" id="pwConfirm"
                                   class="form-control"
                                   placeholder="Ulangi password baru" required>
                            <button type="button" class="btn btn-outline-secondary"
                                    onclick="toggleField('pwConfirm','eyeConfirm')">
                                <i class="bi bi-eye" id="eyeConfirm"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-shield-check me-1"></i> Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function toggleField(inputId, iconId) {
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
