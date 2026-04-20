@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')

@section('content')
<div class="card shadow-sm" style="max-width:560px">
    <div class="card-header bg-white">
        <h6 class="mb-0 fw-semibold">Kategori Baru</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="row g-3">

                <div class="col-12">
                    <label class="form-label small fw-semibold">
                        Nama Kategori <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="e.g. Monitor, Access Point, Printer"
                           autofocus required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-5">
                    <label class="form-label small fw-semibold">
                        Prefix Kode <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="text" name="prefix" id="prefixInput"
                               class="form-control text-uppercase @error('prefix') is-invalid @enderror"
                               value="{{ old('prefix') }}"
                               placeholder="e.g. MON"
                               maxlength="10"
                               oninput="this.value = this.value.toUpperCase().replace(/[^A-Z]/g,'')"
                               required>
                        <span class="input-group-text text-muted">-001</span>
                    </div>
                    @error('prefix')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    <div class="form-text">Hanya huruf kapital, maks 10 karakter</div>
                </div>

                <div class="col-md-7 d-flex align-items-end">
                    <div class="bg-light border rounded px-3 py-2 w-100 text-center">
                        <div class="text-muted small mb-1">Preview kode</div>
                        <code id="codePreview" class="fs-5 text-primary">—</code>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-semibold">
                        Deskripsi <span class="text-muted fw-normal">(opsional)</span>
                    </label>
                    <input type="text" name="description"
                           class="form-control"
                           value="{{ old('description') }}"
                           placeholder="e.g. Perangkat tampilan/display untuk komputer">
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active"
                               id="isActive" value="1"
                               {{ old('is_active', '1') ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold" for="isActive">
                            Aktif <span class="text-muted fw-normal">(tampil di form tambah asset)</span>
                        </label>
                    </div>
                </div>

            </div>
            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Kategori
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Live preview kode dari prefix yang diketik
document.getElementById('prefixInput').addEventListener('input', function () {
    const val = this.value.trim().toUpperCase();
    document.getElementById('codePreview').textContent = val ? val + '-001' : '—';
});

// Trigger on load kalau ada old value
const oldPrefix = '{{ old("prefix") }}';
if (oldPrefix) {
    document.getElementById('codePreview').textContent = oldPrefix.toUpperCase() + '-001';
}
</script>
@endpush
