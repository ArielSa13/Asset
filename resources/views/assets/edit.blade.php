@extends('layouts.app')
@section('title', 'Edit Asset')
@section('page-title', 'Edit Asset')

@section('content')
<div class="card shadow-sm" style="max-width:700px">
    <div class="card-header bg-white">
        <h6 class="mb-0 fw-semibold">Edit: {{ $asset->name }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('assets.update', $asset) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">

                {{-- Kategori --}}
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" id="categorySelect"
                            class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">— Pilih Kategori —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                    data-prefix="{{ $cat->prefix }}"
                                    {{ old('category_id', $asset->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->prefix }}-xxx)
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Kode --}}
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">
                        Kode Aset <span class="text-danger">*</span>
                        <span id="codeSpinner" class="spinner-border spinner-border-sm text-primary ms-1 d-none"></span>
                    </label>
                    <div class="input-group">
                        <input type="text" name="code" id="codeInput"
                               class="form-control @error('code') is-invalid @enderror"
                               value="{{ old('code', $asset->code) }}" required>
                        <button type="button" class="btn btn-outline-secondary"
                                onclick="refreshCode()" title="Generate ulang">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                    @error('code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                {{-- Kondisi --}}
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Kondisi <span class="text-danger">*</span></label>
                    <select name="condition" class="form-select" required>
                        @foreach($conditions as $val => $label)
                            <option value="{{ $val }}" {{ old('condition', $asset->condition) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama --}}
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Nama Asset <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $asset->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Status --}}
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach($statuses as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $asset->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Merek / Brand</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand', $asset->brand) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Model / Tipe</label>
                    <input type="text" name="model" class="form-control" value="{{ old('model', $asset->model) }}">
                </div>

                <div class="col-12">
                    <label class="form-label small fw-semibold">Serial Number <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number', $asset->serial_number) }}">
                </div>

                <div class="col-12">
                    <label class="form-label small fw-semibold">Deskripsi / Catatan <span class="text-muted fw-normal">(opsional)</span></label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $asset->description) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-semibold">
                        <i class="bi bi-geo-alt me-1 text-secondary"></i> Lokasi Penyimpanan <span class="text-muted fw-normal">(opsional)</span>
                    </label>
                    <input type="text" name="location" class="form-control"
                           value="{{ old('location', $asset->location) }}"
                           placeholder="e.g. Gudang IT, Rak A2, Ruang Server, Lantai 2">
                    <div class="form-text">Lokasi fisik asset disimpan saat tidak digunakan.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Foto Asset <span class="text-muted fw-normal">(opsional)</span></label>
                    @if($asset->image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($asset->image) }}" class="img-thumbnail" style="max-height:70px">
                            <div class="form-text">Upload baru untuk mengganti foto</div>
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

            </div>
            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Asset</button>
                <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const generateCodeUrl = '{{ route("assets.generate-code") }}';
const codeInput       = document.getElementById('codeInput');
const codeSpinner     = document.getElementById('codeSpinner');

// Di halaman edit, TIDAK auto-generate saat ganti kategori
// User harus klik tombol refresh secara manual jika mau kode baru
async function refreshCode() {
    const catId = document.getElementById('categorySelect').value;
    if (!catId) { alert('Pilih kategori terlebih dahulu.'); return; }

    codeSpinner.classList.remove('d-none');
    codeInput.disabled = true;
    try {
        const res  = await fetch(`${generateCodeUrl}?category_id=${catId}`);
        const data = await res.json();
        codeInput.value = data.code;
    } catch(e) { console.error(e); }
    finally {
        codeSpinner.classList.add('d-none');
        codeInput.disabled = false;
    }
}
</script>
@endpush
