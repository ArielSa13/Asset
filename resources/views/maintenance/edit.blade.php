@extends('layouts.app')
@section('title', 'Edit Maintenance')
@section('page-title', 'Edit Maintenance')

@section('content')
<div class="card shadow-sm" style="max-width:760px">
    <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">Edit Maintenance Record</h6></div>
    <div class="card-body">
        <form action="{{ route('maintenance.update', $maintenance) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold">Asset <span class="text-danger">*</span></label>
                    <select name="asset_id" class="form-select" required>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ old('asset_id', $maintenance->asset_id) == $asset->id ? 'selected' : '' }}>
                            {{ $asset->name }} ({{ $asset->code }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Type</label>
                    <select name="type" class="form-select" required>
                        @foreach($types as $val => $label)
                        <option value="{{ $val }}" {{ old('type', $maintenance->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach($statuses as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $maintenance->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Cost (Rp)</label>
                    <input type="number" name="cost" class="form-control" value="{{ old('cost', $maintenance->cost) }}" min="0">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Description</label>
                    <input type="text" name="description" class="form-control" value="{{ old('description', $maintenance->description) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Vendor Name</label>
                    <input type="text" name="vendor_name" class="form-control" value="{{ old('vendor_name', $maintenance->vendor_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Vendor Phone</label>
                    <input type="text" name="vendor_phone" class="form-control" value="{{ old('vendor_phone', $maintenance->vendor_phone) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Started At</label>
                    <input type="date" name="started_at" class="form-control" value="{{ old('started_at', $maintenance->started_at->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Completed At</label>
                    <input type="date" name="completed_at" class="form-control" value="{{ old('completed_at', $maintenance->completed_at?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">

                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $maintenance->notes) }}</textarea>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('maintenance.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
