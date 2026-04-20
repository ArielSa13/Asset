<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman Asset — AssetMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); min-height: 100vh; display:flex; align-items:center; justify-content:center; padding: 1.5rem; }
        .form-card { background: #fff; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,.25); max-width: 560px; width: 100%; }
        .form-header { background: linear-gradient(135deg, #1e3a5f, #2563eb); border-radius: 16px 16px 0 0; padding: 1.75rem; color: #fff; text-align: center; }
        .form-header h4 { margin: 0; font-weight: 700; }
        .form-header p { margin: .3rem 0 0; opacity: .8; font-size: .9rem; }
        .form-body { padding: 1.75rem; }
        .asset-option { border: 2px solid #e9ecef; border-radius: 10px; padding: .75rem 1rem; cursor: pointer; transition: all .2s; margin-bottom: .5rem; }
        .asset-option:hover { border-color: #2563eb; background: #f0f4ff; }
        .asset-option input[type=radio] { accent-color: #2563eb; }
        .asset-option.selected { border-color: #2563eb; background: #f0f4ff; }
        .badge-available { background: #d1fae5; color: #065f46; font-size: .72rem; padding: .2rem .5rem; border-radius: 20px; }
    </style>
</head>
<body>
<div class="form-card">
    <div class="form-header">
        <div style="font-size:2rem; margin-bottom:.5rem">📋</div>
        <h4>Form Request Peminjaman Asset</h4>
        <p>Isi form ini untuk mengajukan peminjaman perangkat IT</p>
    </div>
    <div class="form-body">
        @if($errors->any())
        <div class="alert alert-danger py-2 px-3 mb-3 small">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('loan-requests.public.store') }}" method="POST">
            @csrf

            {{-- Data Peminjam --}}
            <h6 class="fw-semibold text-muted mb-3" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.05em">
                <i class="bi bi-person me-1"></i> Data Peminjam
            </h6>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="borrower_name" class="form-control @error('borrower_name') is-invalid @enderror"
                       value="{{ old('borrower_name') }}" placeholder="Nama lengkap kamu" required>
                @error('borrower_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label small fw-semibold">Departemen</label>
                    <input type="text" name="borrower_department" class="form-control"
                           value="{{ old('borrower_department') }}" placeholder="e.g. HRD, Finance, Marketing">
                </div>
                <div class="col-sm-6">
                    <label class="form-label small fw-semibold">No. HP / WhatsApp</label>
                    <input type="text" name="borrower_phone" class="form-control"
                           value="{{ old('borrower_phone') }}" placeholder="08xx-xxxx-xxxx">
                </div>
            </div>

            <hr class="my-3">

            {{-- Pilih Asset --}}
            <h6 class="fw-semibold text-muted mb-3" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.05em">
                <i class="bi bi-pc-display me-1"></i> Pilih Asset
            </h6>

            @if($assets->isEmpty())
                <div class="alert alert-warning text-center py-3">
                    <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                    Tidak ada asset yang tersedia saat ini.
                </div>
            @else
                @error('asset_id')
                <div class="alert alert-danger py-2 px-3 small mb-2">{{ $message }}</div>
                @enderror

                {{-- Filter kategori --}}
                <div class="mb-2 d-flex gap-2 flex-wrap" id="catFilters">
                    <button type="button" class="btn btn-sm btn-primary cat-filter active" data-cat="all">Semua</button>
                    @foreach($assets->groupBy(fn($a) => $a->assetCategory->name ?? 'Lainnya') as $catName => $catAssets)
                    <button type="button" class="btn btn-sm btn-outline-secondary cat-filter" data-cat="{{ $catName }}">
                        {{ $catName }} <span class="badge bg-secondary ms-1">{{ $catAssets->count() }}</span>
                    </button>
                    @endforeach
                </div>

                <div style="max-height:280px;overflow-y:auto" class="pe-1" id="assetList">
                    @foreach($assets as $asset)
                    <div class="asset-wrap" data-cat="{{ $asset->assetCategory->name ?? 'Lainnya' }}">
                    <label class="asset-option d-flex align-items-center gap-3 {{ old('asset_id') == $asset->id ? 'selected' : '' }}">
                        <input type="radio" name="asset_id" value="{{ $asset->id }}"
                               {{ old('asset_id') == $asset->id ? 'checked' : '' }} required>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold small">{{ $asset->name }}</div>
                            <div class="text-muted" style="font-size:.75rem">
                                <code>{{ $asset->code }}</code>
                                @if($asset->brand) · {{ $asset->brand }}@endif
                                @if($asset->model) {{ $asset->model }}@endif
                            </div>
                        </div>
                        <span class="badge-available">Tersedia</span>
                    </label>
                    </div>
                    @endforeach
                </div>
            @endif

            <hr class="my-3">

            {{-- Keperluan --}}
            <h6 class="fw-semibold text-muted mb-3" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.05em">
                <i class="bi bi-chat-text me-1"></i> Keperluan
            </h6>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Keperluan Peminjaman <span class="text-danger">*</span></label>
                <input type="text" name="purpose" class="form-control @error('purpose') is-invalid @enderror"
                       value="{{ old('purpose') }}" placeholder="e.g. Presentasi ke klien, WFH, Training" required>
                @error('purpose')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label small fw-semibold">Catatan Tambahan <span class="text-muted fw-normal">(opsional)</span></label>
                <textarea name="notes" class="form-control" rows="2"
                          placeholder="Informasi tambahan yang perlu diketahui IT Support">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" @if($assets->isEmpty()) disabled @endif>
                <i class="bi bi-send me-2"></i> Kirim Permintaan
            </button>
        </form>

        <div class="text-center mt-3 text-muted" style="font-size:.75rem">
            Permintaan akan diproses oleh tim IT Support. Kamu akan dihubungi setelah disetujui.
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Highlight asset yang dipilih
document.querySelectorAll('.asset-option input[type=radio]').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('.asset-option').forEach(el => el.classList.remove('selected'));
        this.closest('.asset-option').classList.add('selected');
    });
});

// Filter kategori
document.querySelectorAll('.cat-filter').forEach(btn => {
    btn.addEventListener('click', function () {
        // Reset semua tombol
        document.querySelectorAll('.cat-filter').forEach(b => {
            b.classList.remove('btn-primary');
            b.classList.add('btn-outline-secondary');
        });
        // Aktifkan tombol yang diklik
        this.classList.add('btn-primary');
        this.classList.remove('btn-outline-secondary');

        const cat = this.dataset.cat;
        document.querySelectorAll('.asset-wrap').forEach(wrap => {
            if (cat === 'all' || wrap.dataset.cat === cat) {
                wrap.style.display = '';
            } else {
                wrap.style.display = 'none';
            }
        });
    });
});
</script>
</body>
</html>
