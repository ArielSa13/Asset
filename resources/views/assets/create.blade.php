@extends('layouts.app')
@section('title', 'Tambah Asset')
@section('page-title', 'Tambah Asset')

@section('content')
    <div class="card shadow-sm" style="max-width:700px">
        <div class="card-header bg-white">
            <h6 class="mb-0 fw-semibold">Asset Baru</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">

                    {{-- Kategori --}}
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold">
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <select name="category_id" id="categorySelect"
                            class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">— Pilih Kategori —</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" data-prefix="{{ $cat->prefix }}"
                                    {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                    <span class="text-muted">({{ $cat->prefix }}-xxx)</span>
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <a href="{{ route('categories.create') }}" target="_blank" class="text-decoration-none">
                                <i class="bi bi-plus-circle me-1"></i>Tambah kategori baru
                            </a>
                        </div>
                    </div>

                    {{-- Kode Aset --}}
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">
                            Kode Aset <span class="text-danger">*</span>
                            <span id="codeSpinner" class="spinner-border spinner-border-sm text-primary ms-1 d-none"></span>
                        </label>
                        <div class="input-group">
                            <input type="text" name="code" id="codeInput"
                                class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}"
                                placeholder="Pilih kategori dulu…" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="refreshCode()"
                                title="Generate ulang">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                        <div class="form-text"><i class="bi bi-magic"></i> Auto-generate, bisa diedit manual</div>
                        @error('code')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kondisi --}}
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Kondisi <span class="text-danger">*</span></label>
                        <select name="condition" class="form-select" required>
                            @foreach ($conditions as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('condition', 'good') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nama --}}
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Nama Asset <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="e.g. Monitor LG 24 inch" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            @foreach ($statuses as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('status', 'available') === $val ? 'selected' : '' }}>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Merek & Model --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Merek / Brand</label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand') }}"
                            placeholder="e.g. LG, Dell, TP-Link">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Model / Tipe</label>
                        <input type="text" name="model" class="form-control" value="{{ old('model') }}"
                            placeholder="e.g. 24MK430H-B">
                    </div>

                    {{-- Serial Number --}}
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Serial Number</label>
                        <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number') }}"
                            placeholder="Nomor seri dari stiker/kardus perangkat">
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">
                        <label class="form-label small fw-semibold">
                            Deskripsi / Catatan <span class="text-muted fw-normal">(opsional)</span>
                        </label>
                        <textarea name="description" class="form-control" rows="2"
                            placeholder="Spesifikasi tambahan, catatan khusus, dll">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">
                            <i class="bi bi-geo-alt me-1 text-secondary"></i> Lokasi Penyimpanan
                        </label>
                        <input type="text" name="location" class="form-control" value="{{ old('location') }}"
                            placeholder="e.g. Gudang IT, Rak A2, Ruang Server, Lantai 2">
                        <div class="form-text">Lokasi fisik asset disimpan saat tidak digunakan.</div>
                    </div>

                    {{-- Foto --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">
                            Foto Asset <span class="text-muted fw-normal">(opsional)</span>
                        </label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                </div>
                <hr class="my-4">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Asset
                    </button>
                    <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const generateCodeUrl = '{{ route('assets.generate-code') }}';
        const categorySelect = document.getElementById('categorySelect');
        const codeInput = document.getElementById('codeInput');
        const codeSpinner = document.getElementById('codeSpinner');

        // Saat kategori dipilih → generate kode otomatis
        categorySelect.addEventListener('change', function() {
            if (!this.value) {
                codeInput.value = '';
                codeInput.placeholder = 'Pilih kategori dulu…';
                return;
            }
            fetchCode(this.value);
        });

        async function fetchCode(categoryId) {
            codeSpinner.classList.remove('d-none');
            codeInput.disabled = true;
            codeInput.placeholder = 'Generating…';
            try {
                const res = await fetch(`${generateCodeUrl}?category_id=${categoryId}`);
                const data = await res.json();
                codeInput.value = data.code;
            } catch (e) {
                console.error(e);
            } finally {
                codeSpinner.classList.add('d-none');
                codeInput.disabled = false;
                codeInput.placeholder = '';
            }
        }

        function refreshCode() {
            if (!categorySelect.value) {
                alert('Pilih kategori terlebih dahulu.');
                return;
            }
            fetchCode(categorySelect.value);
        }

        // Jika validasi gagal dan ada old category_id, generate ulang kode
        @if (old('category_id') && !old('code'))
            document.addEventListener('DOMContentLoaded', () => fetchCode('{{ old('category_id') }}'));
        @endif
    </script>
@endpush
