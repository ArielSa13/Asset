@extends('layouts.app')
@section('title', 'Assets')
@section('page-title', 'Asset Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Daftar Asset</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('assets.pdf') }}{{ request()->getQueryString() ? '?'.request()->getQueryString() : '' }}"
           class="btn btn-outline-danger btn-sm">
            <i class="bi bi-file-pdf me-1"></i>Export PDF
        </a>
        <a href="{{ route('assets.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Asset
        </a>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari nama, kode, serial number..."
                       value="{{ request('search') }}">
            </div>

            <div class="col-md-2">
    <select name="condition" class="form-select form-select-sm">
        <option value="">Semua Kondisi</option>
        @foreach($conditions as $val => $label)
            <option value="{{ $val }}" {{ request('condition') === $val ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $val => $label)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 🔥 DROPDOWN JUMLAH DATA --}}
            <div class="col-md-2">
                <select name="per_page" class="form-select form-select-sm">
                    <option value="10" {{ request('per_page')=='10'?'selected':'' }}>10</option>
                    <option value="25" {{ request('per_page')=='25'?'selected':'' }}>25</option>
                    <option value="50" {{ request('per_page')=='50'?'selected':'' }}>50</option>
                    <option value="100" {{ request('per_page')=='100'?'selected':'' }}>100</option>
                    <option value="all" {{ request('per_page')=='all'?'selected':'' }}>Semua</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button class="btn btn-primary btn-sm flex-fill">Filter</button>
                @if(request()->hasAny(['search','status','category_id','per_page']))
                    <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama Asset</th>
                    <th>Kategori</th>
                    <th>Merek / Model</th>
                    <th>Serial Number</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Lokasi</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assets as $asset)
                <tr>
                    {{-- 🔥 FIX NOMOR --}}
                    <td class="text-muted small">
                        {{ method_exists($assets, 'firstItem') ? $assets->firstItem() + $loop->index : $loop->iteration }}
                    </td>

                    <td><code class="small fw-bold">{{ $asset->code }}</code></td>

                    <td>
                        <div class="fw-semibold small">{{ $asset->name }}</div>
                        @if($asset->description)
                            <div class="text-muted" style="font-size:.72rem">
                                {{ Str::limit($asset->description, 40) }}
                            </div>
                        @endif
                    </td>

                    <td>
                        @if($asset->assetCategory)
                            <span class="badge bg-secondary">{{ $asset->assetCategory->name }}</span>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>

                    <td class="small">{{ trim($asset->brand . ' ' . $asset->model) ?: '-' }}</td>
                    <td class="small text-muted">{{ $asset->serial_number ?: '-' }}</td>

                    <td>
                        <span class="badge bg-{{ $asset->condition_badge }}">
                            {{ ucfirst($asset->condition) }}
                        </span>
                    </td>

                    <td>
                        <span class="badge bg-{{ $asset->status_badge }}">
                            {{ ucwords(str_replace('_',' ',$asset->status)) }}
                        </span>
                    </td>

                    <td class="small">
                        @if($asset->location)
                            @if($asset->status === 'in_use')
                                <span class="text-primary">
                                    <i class="bi bi-person-fill me-1"></i>{{ Str::limit($asset->location, 25) }}
                                </span>
                            @else
                                <span class="text-muted">
                                    <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($asset->location, 25) }}
                                </span>
                            @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    <td class="text-end">
                        <a href="{{ route('assets.show', $asset) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus asset {{ $asset->code }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-5">
                        Belum ada asset.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 🔥 PAGINATION AMAN --}}
    @if($assets instanceof \Illuminate\Pagination\LengthAwarePaginator && $assets->hasPages())
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between">
            <div class="text-muted small">
                Menampilkan {{ $assets->firstItem() }} - {{ $assets->lastItem() }}
                dari {{ $assets->total() }}
            </div>
            {{ $assets->links() }}
        </div>
    </div>
    @endif
</div>
@endsection