@extends('layouts.app')
@section('title', 'Log Maintenance')
@section('page-title', 'Log Maintenance')

@section('content')
<div class="card shadow-sm" style="max-width:760px">
    <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">New Maintenance Record</h6></div>
    <div class="card-body">
        <form action="{{ route('maintenance.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold">Asset <span class="text-danger">*</span></label>
                    <select name="asset_id" class="form-select @error('asset_id') is-invalid @enderror" required>
                        <option value="">-- Select Asset --</option>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ (old('asset_id') == $asset->id || request('asset_id') == $asset->id) ? 'selected' : '' }}>
                            {{ $asset->name }} ({{ $asset->code }}) - {{ ucwords(str_replace('_',' ',$asset->status)) }}
                        </option>
                        @endforeach
                    </select>
                    @error('asset_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        @foreach($types as $val => $label)
                        <option value="{{ $val }}" {{ old('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach($statuses as $val => $label)
                        <option value="{{ $val }}" {{ old('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Cost (Rp)</label>
                    <input type="number" name="cost" class="form-control" value="{{ old('cost') }}" min="0">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Description <span class="text-danger">*</span></label>
                    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" required>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Vendor Name</label>
                    <input type="text" name="vendor_name" class="form-control" value="{{ old('vendor_name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Vendor Phone</label>
                    <input type="text" name="vendor_phone" class="form-control" value="{{ old('vendor_phone') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Started At <span class="text-danger">*</span></label>
                    <input type="date" name="started_at" class="form-control @error('started_at') is-invalid @enderror" value="{{ old('started_at', now()->format('Y-m-d')) }}" required>
                    @error('started_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Completed At</label>
                    <input type="date" name="completed_at" class="form-control" value="{{ old('completed_at') }}">
                </div>
                <div class="col-md-4">

                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Record</button>
                <a href="{{ route('maintenance.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
