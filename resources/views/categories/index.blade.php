@extends('layouts.app')
@section('title', 'Kategori Asset')
@section('page-title', 'Kategori Asset')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Kelola Kategori</h5>
        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Kategori</th>
                        <th>Prefix Kode</th>
                        <th>Contoh Kode</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Asset</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td class="text-muted small">{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $cat->name }}</td>
                            <td>
                                <span class="badge bg-dark fs-6 font-monospace">{{ $cat->prefix }}-</span>
                            </td>
                            <td class="text-muted small font-monospace">
                                {{ $cat->prefix }}-001, {{ $cat->prefix }}-002...
                            </td>
                            <td class="small text-muted">{{ $cat->description ?: '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $cat->assets_count > 0 ? 'primary' : 'secondary' }}">
                                    {{ $cat->assets_count }} asset
                                </span>
                            </td>
                            <td>
                                @if ($cat->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Non-aktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if ($cat->assets_count === 0)
                                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled
                                        title="Tidak bisa dihapus — masih ada asset">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-tag fs-2 d-block mb-2"></i>
                                Belum ada kategori.
                                <a href="{{ route('categories.create') }}">Tambah sekarang</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($categories->hasPages())
            <div class="card-footer bg-white">{{ $categories->links() }}</div>
        @endif
    </div>

    {{-- Info box --}}
    <div class="alert alert-info border-0 mt-3 small">
        <i class="bi bi-info-circle me-1"></i>
        <strong>Tips:</strong> Prefix kode menentukan awalan nomor asset.
        Contoh: kategori <strong>Monitor</strong> dengan prefix <strong>MON</strong>
        akan menghasilkan kode <strong>MON-001</strong>, <strong>MON-002</strong>, dst.
        Kategori yang sudah memiliki asset <strong>tidak bisa dihapus</strong>,
        tapi bisa di-nonaktifkan agar tidak muncul di form tambah asset.
    </div>
@endsection
