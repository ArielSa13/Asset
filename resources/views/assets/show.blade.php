@extends('layouts.app')
@section('title', $asset->name)
@section('page-title', 'Asset Detail')

@section('content')
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    @if ($asset->image)
                        <img src="{{ Storage::url($asset->image) }}" alt="{{ $asset->name }}" class="img-fluid rounded mb-3"
                            style="max-height:200px">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3"
                            style="height:150px">
                            <i class="bi bi-image text-muted fs-1"></i>
                        </div>
                    @endif
                    <h5 class="fw-bold">{{ $asset->name }}</h5>
                    <code class="text-muted">{{ $asset->code }}</code>
                    <div class="mt-2">
                        <span
                            class="badge bg-{{ $asset->status_badge }} me-1">{{ ucwords(str_replace('_', ' ', $asset->status)) }}</span>
                        <span class="badge bg-{{ $asset->condition_badge }}">{{ ucfirst($asset->condition) }}</span>
                    </div>
                </div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between"><span
                            class="text-muted">Category</span><strong>{{ $asset->category }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span
                            class="text-muted">Brand</span><strong>{{ $asset->brand ?: '-' }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span
                            class="text-muted">Model</span><strong>{{ $asset->model ?: '-' }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Serial
                            No.</span><strong>{{ $asset->serial_number ?: '-' }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span
                            class="text-muted">Location</span><strong>{{ $asset->location ?: '-' }}</strong></li>
                    @if ($asset->purchase_date)
                        <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Purchase
                                Date</span><strong>{{ $asset->purchase_date->format('d M Y') }}</strong></li>
                    @endif
                    @if ($asset->purchase_price)
                        <li class="list-group-item d-flex justify-content-between"><span
                                class="text-muted">Price</span><strong>Rp
                                {{ number_format($asset->purchase_price, 0, ',', '.') }}</strong></li>
                    @endif
                </ul>
                <div class="card-footer bg-white d-flex gap-2">
                    <a href="{{ route('assets.edit', $asset) }}" class="btn btn-primary btn-sm flex-fill"><i
                            class="bi bi-pencil me-1"></i>Edit</a>
                    <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">Back</a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            {{-- Loan History --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Loan History</span>
                    @if ($asset->status === 'available')
                        <a href="{{ route('loans.create') }}?asset_id={{ $asset->id }}"
                            class="btn btn-sm btn-outline-primary">+ New Loan</a>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Borrower</th>
                                <th>Borrowed</th>
                                <th>Returned</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asset->loans as $loan)
                                <tr>
                                    <td class="small">{{ $loan->borrower_name }}<br><span class="text-muted"
                                            style="font-size:.75rem">{{ $loan->borrower_department }}</span></td>
                                    <td class="small">{{ $loan->borrowed_at->format('d M Y') }}</td>
                                    <td class="small">{{ $loan->returned_at?->format('d M Y') ?? '-' }}</td>
                                    <td><span class="badge bg-{{ $loan->status_badge }}">{{ $loan->status_label }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No loan history</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Maintenance History --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Maintenance History</span>
                    <a href="{{ route('maintenance.create') }}?asset_id={{ $asset->id }}"
                        class="btn btn-sm btn-outline-warning">+ Log Maintenance</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Vendor</th>
                                <th>Cost</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asset->maintenances as $m)
                                <tr>
                                    <td class="small">{{ $m->type_label ?? ucfirst($m->type) }}</td>
                                    <td class="small">{{ Str::limit($m->description, 40) }}</td>
                                    <td class="small">{{ $m->vendor_name ?: '-' }}</td>
                                    <td class="small">{{ $m->cost ? 'Rp ' . number_format($m->cost, 0, ',', '.') : '-' }}</td>
                                    <td><span
                                            class="badge bg-{{ $m->status_badge }}">{{ ucwords(str_replace('_', ' ', $m->status)) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No maintenance records</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
