@extends('layouts.app')
@section('title', 'Maintenance')
@section('page-title', 'Maintenance Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Maintenance Records</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('maintenance.pdf') }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-file-pdf me-1"></i>Export PDF</a>
        <a href="{{ route('maintenance.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Log Maintenance</a>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search asset, vendor, description..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    @foreach($statuses as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach($types as $val => $label)
                    <option value="{{ $val }}" {{ request('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="alert alert-light border d-flex align-items-center gap-2 py-2">
    <i class="bi bi-cash-coin text-success"></i>
    <span class="small">Total Maintenance Cost (Completed): <strong>Rp {{ number_format($totalCost, 0, ',', '.') }}</strong></span>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Asset</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Vendor</th>
                    <th>Cost</th>
                    <th>Started</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($maintenances as $m)
                <tr>
                    <td class="small fw-semibold">{{ $m->asset->name ?? '-' }}</td>
                    <td><span class="badge bg-secondary">{{ ($m->type_label ?? ucfirst($m->type)) }}</span></td>
                    <td class="small">{{ Str::limit($m->description, 40) }}</td>
                    <td class="small text-muted">{{ $m->vendor_name ?: '-' }}</td>
                    <td class="small">{{ $m->cost ? 'Rp '.number_format($m->cost,0,',','.') : '-' }}</td>
                    <td class="small">{{ $m->started_at->format('d M Y') }}</td>
                    <td><span class="badge bg-{{ $m->status_badge }}">{{ ucwords(str_replace('_',' ',$m->status)) }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('maintenance.show', $m) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('maintenance.edit', $m) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('maintenance.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="bi bi-tools fs-3 d-block mb-2"></i>
                        No maintenance records found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($maintenances->hasPages())
    <div class="card-footer bg-white">{{ $maintenances->links() }}</div>
    @endif
</div>
@endsection
