@extends('layouts.app')
@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')
    <div class="card shadow-sm" style="max-width:560px">
        <div class="card-header bg-white">
            <h6 class="mb-0 fw-semibold">Edit: {{ $category->name }}</h6>
        </div>
        <div class="card-body">

            @if ($category->assets_count > 0)
                <div class="alert alert-warning small py-2 mb-3">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Kategori ini memiliki <strong>{{ $category->assets()->count() }} asset</strong>.
                    Mengubah prefix <strong>tidak akan</strong> mengubah kode asset yang sudah ada.
                </div>
            @endif

            <form action="{{ route('categories.update', $category) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label small fw-semibold">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $category->name) }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-5">
                        <label class="form-label small fw-semibold">
                            Prefix Kode <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="text" name="prefix" id="prefixInput"
                                class="form-control text-uppercase @error('prefix') is-invalid @enderror"
                                value="{{ old('prefix', $category->prefix) }}" maxlength="10"
                                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z]/g,'')" required>
                            <span class="input-group-text text-muted">-001</span>
                        </div>
                        @error('prefix')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-7 d-flex align-items-end">
                        <div class="bg-light border rounded px-3 py-2 w-100 text-center">
                            <div class="text-muted small mb-1">Preview kode berikutnya</div>
                            <code id="codePreview" class="fs-5 text-primary">{{ $category->prefix }}-001</code>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">
                            Deskripsi <span class="text-muted fw-normal">(opsional)</span>
                        </label>
                        <input type="text" name="description" class="form-control"
                            value="{{ old('description', $category->description) }}">
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                                {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="isActive">
                                Aktif <span class="text-muted fw-normal">(tampil di form tambah asset)</span>
                            </label>
                        </div>
                    </div>

                </div>
                <hr class="my-4">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update Kategori
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('prefixInput').addEventListener('input', function() {
            const val = this.value.trim().toUpperCase();
            document.getElementById('codePreview').textContent = val ? val + '-001' : '—';
        });
    </script>
@endpush
