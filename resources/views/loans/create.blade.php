@extends('layouts.app')

@section('title', 'New Loan')
@section('page-title', 'Create Loan')

@section('content')
<div class="card shadow-sm" style="max-width:760px">
    <div class="card-header bg-white">
        <h6 class="mb-0 fw-semibold">New Asset Loan</h6>
    </div>

    <div class="card-body">
        <form action="{{ route('loans.store') }}" method="POST">
            @csrf

            <div class="row g-3">

                {{-- Asset --}}
                <div class="col-12">
                    <label class="form-label small fw-semibold">
                        Asset <span class="text-danger">*</span>
                    </label>

                    <select
                        name="asset_id"
                        class="form-select @error('asset_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Select Available Asset --</option>

                        @foreach($assets as $asset)
                            <option
                                value="{{ $asset->id }}"
                                {{ (old('asset_id') == $asset->id || request('asset_id') == $asset->id) ? 'selected' : '' }}
                            >
                                {{ $asset->name }} ({{ $asset->code }})
                            </option>
                        @endforeach
                    </select>

                    @error('asset_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Borrower Name --}}
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        Borrower Name <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="borrower_name"
                        class="form-control @error('borrower_name') is-invalid @enderror"
                        value="{{ old('borrower_name') }}"
                        required
                    >

                    @error('borrower_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Position --}}
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        Position <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="borrower_position"
                        class="form-control @error('borrower_position') is-invalid @enderror"
                        value="{{ old('borrower_position') }}"
                        placeholder=""
                        required
                    >

                    @error('borrower_position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Department --}}
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        Department <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="borrower_department"
                        class="form-control @error('borrower_department') is-invalid @enderror"
                        value="{{ old('borrower_department') }}"
                        required
                    >

                    @error('borrower_department')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Phone --}}
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        Phone
                    </label>

                    <input
                        type="text"
                        name="borrower_phone"
                        class="form-control @error('borrower_phone') is-invalid @enderror"
                        value="{{ old('borrower_phone') }}"
                    >

                    @error('borrower_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Borrow Date --}}
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        Borrow Date <span class="text-danger">*</span>
                    </label>

                    <input
                        type="datetime-local"
                        name="borrowed_at"
                        class="form-control @error('borrowed_at') is-invalid @enderror"
                        value="{{ old('borrowed_at', now()->format('Y-m-d\TH:i')) }}"
                        required
                    >

                    @error('borrowed_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Expected Return
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        Expected Return
                    </label>

                    <input
                        type="datetime-local"
                        name="expected_return_at"
                        class="form-control @error('expected_return_at') is-invalid @enderror"
                        value="{{ old('expected_return_at') }}"
                    >

                    @error('expected_return_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> --}}


                {{-- Condition Before --}}
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">
                        Condition Before <span class="text-danger">*</span>
                    </label>

                    <select
                        name="condition_before"
                        class="form-select @error('condition_before') is-invalid @enderror"
                        required
                    >
                        <option value="good" {{ old('condition_before', 'good') == 'good' ? 'selected' : '' }}>
                            Good
                        </option>

                        <option value="fair" {{ old('condition_before') == 'fair' ? 'selected' : '' }}>
                            Fair
                        </option>

                        <option value="poor" {{ old('condition_before') == 'poor' ? 'selected' : '' }}>
                            Poor
                        </option>

                        <option value="broken" {{ old('condition_before') == 'broken' ? 'selected' : '' }}>
                            Broken
                        </option>
                    </select>

                    @error('condition_before')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Spacer --}}
                <div class="col-md-8"></div>


                {{-- Purpose --}}
                <div class="col-12">
                    <label class="form-label small fw-semibold">
                        Purpose <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="purpose"
                        class="form-control @error('purpose') is-invalid @enderror"
                        value="{{ old('purpose') }}"
                        required
                    >

                    @error('purpose')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Notes --}}
                <div class="col-12">
                    <label class="form-label small fw-semibold">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        class="form-control @error('notes') is-invalid @enderror"
                        rows="2"
                    >{{ old('notes') }}</textarea>

                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <hr class="my-4">

            {{-- Buttons --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>
                    Create Loan
                </button>

                <a
                    href="{{ route('loans.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection