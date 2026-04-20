@extends('layouts.app')
@section('title', 'New Loan')
@section('page-title', 'Create Loan')

@section('content')
<div class="card shadow-sm" style="max-width:760px">
    <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">New Asset Loan</h6></div>
    <div class="card-body">
        <form action="{{ route('loans.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold">Asset <span class="text-danger">*</span></label>
                    <select name="asset_id" class="form-select @error('asset_id') is-invalid @enderror" required>
                        <option value="">-- Select Available Asset --</option>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ (old('asset_id') == $asset->id || request('asset_id') == $asset->id) ? 'selected' : '' }}>
                            {{ $asset->name }} ({{ $asset->code }})
                        </option>
                        @endforeach
                    </select>
                    @error('asset_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Borrower Name <span class="text-danger">*</span></label>
                    <input type="text" name="borrower_name" class="form-control @error('borrower_name') is-invalid @enderror" value="{{ old('borrower_name') }}" required>
                    @error('borrower_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Department <span class="text-danger">*</span></label>
                    <input type="text" name="borrower_department" class="form-control @error('borrower_department') is-invalid @enderror" value="{{ old('borrower_department') }}" required>
                    @error('borrower_department')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Phone</label>
                    <input type="text" name="borrower_phone" class="form-control" value="{{ old('borrower_phone') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Borrow Date <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="borrowed_at" class="form-control @error('borrowed_at') is-invalid @enderror" value="{{ old('borrowed_at', now()->format('Y-m-d\TH:i')) }}" required>
                    @error('borrowed_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Expected Return</label>
                    <input type="datetime-local" name="expected_return_at" class="form-control" value="{{ old('expected_return_at') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Condition Before <span class="text-danger">*</span></label>
                    <select name="condition_before" class="form-select" required>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                        <option value="broken">Broken</option>
                    </select>
                </div>
                <div class="col-md-8">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Purpose <span class="text-danger">*</span></label>
                    <input type="text" name="purpose" class="form-control @error('purpose') is-invalid @enderror" value="{{ old('purpose') }}" required>
                    @error('purpose')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Create Loan</button>
                <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
