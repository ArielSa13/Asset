@extends('layouts.app')
@section('title', 'Loan Detail')
@section('page-title', 'Loan Detail')

@section('content')
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">Loan #{{ $loan->id }}</h6>
                    <span class="badge bg-{{ $loan->status_badge }} fs-6">{{ $loan->status_label }}</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Asset</small>
                            <strong>{{ $loan->asset->name }}</strong>
                            <div class="text-muted small">{{ $loan->asset->code }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Borrower</small>
                            <strong>{{ $loan->borrower_name }}</strong>
                            <div class="text-muted small">{{ $loan->borrower_department }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Phone</small>
                            <span>{{ $loan->borrower_phone ?: '-' }}</span>
                        </div>
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Borrowed At</small>
                            <span>{{ $loan->borrowed_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Expected Return</small>
                            <span
                                class="{{ $loan->isOverdue() ? 'text-danger fw-bold' : '' }}">{{ $loan->expected_return_at?->format('d M Y H:i') ?? '-' }}</span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Returned At</small>
                            <span class="text-success">{{ $loan->returned_at?->format('d M Y H:i') ?? '-' }}</span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Condition Before</small>
                            <span class="badge bg-success">{{ ucfirst($loan->condition_before) }}</span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Condition After</small>
                            <span
                                class="badge bg-info">{{ $loan->condition_after ? ucfirst($loan->condition_after) : '-' }}</span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Purpose</small>
                            <span>{{ $loan->purpose }}</span>
                        </div>
                        @if ($loan->notes)
                            <div class="col-12">
                                <small class="text-muted d-block">Notes</small>
                                <span>{{ $loan->notes }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white d-flex gap-2">
                    <a href="{{ route('loans.pdf', $loan) }}" class="btn btn-outline-info btn-sm"><i
                            class="bi bi-file-pdf me-1"></i> Export PDF</a>
                    <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary btn-sm">Back to List</a>
                </div>
            </div>
        </div>
    </div>
@endsection
