@extends('layouts.app')
@section('title', 'Maintenance Detail')
@section('page-title', 'Maintenance Detail')

@section('content')
<div class="card shadow-sm" style="max-width:640px">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold">Maintenance Record #{{ $maintenance->id }}</h6>
        <span class="badge bg-{{ $maintenance->status_badge }}">{{ ucwords(str_replace('_',' ',$maintenance->status)) }}</span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <small class="text-muted d-block">Asset</small>
                <strong>{{ $maintenance->asset->name }}</strong>
                <div class="text-muted small">{{ $maintenance->asset->code }}</div>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">Type</small>
                <span class="badge bg-secondary">{{ ($maintenance->type_label ?? ucfirst($maintenance->type)) }}</span>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">Cost</small>
                <strong>{{ $maintenance->cost ? 'Rp '.number_format($maintenance->cost,0,',','.') : '-' }}</strong>
            </div>
            <div class="col-12">
                <small class="text-muted d-block">Description</small>
                <span>{{ $maintenance->description }}</span>
            </div>
            <div class="col-md-6">
                <small class="text-muted d-block">Vendor</small>
                <span>{{ $maintenance->vendor_name ?: '-' }}</span>
                @if($maintenance->vendor_phone)<div class="text-muted small">{{ $maintenance->vendor_phone }}</div>@endif
            </div>
            <div class="col-md-6">

            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">Started</small>
                <span>{{ $maintenance->started_at->format('d M Y') }}</span>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">Completed</small>
                <span>{{ $maintenance->completed_at?->format('d M Y') ?? '-' }}</span>
            </div>
            @if($maintenance->notes)
            <div class="col-12">
                <small class="text-muted d-block">Notes</small>
                <span>{{ $maintenance->notes }}</span>
            </div>
            @endif
        </div>
    </div>
    <div class="card-footer bg-white d-flex gap-2">
        <a href="{{ route('maintenance.edit', $maintenance) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="{{ route('maintenance.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>
</div>
@endsection
