@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')
<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center py-5">
                <i class="bi bi-tools fs-1 text-warning mb-3 d-block"></i>
                <h5 class="fw-semibold">Maintenance Report</h5>
                <p class="text-muted small">Export all maintenance activities with cost summary in PDF format.</p>
                <a href="{{ route('maintenance.pdf') }}" class="btn btn-warning">
                    <i class="bi bi-file-pdf me-1"></i> Download PDF
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center py-5">
                <i class="bi bi-arrow-left-right fs-1 text-info mb-3 d-block"></i>
                <h5 class="fw-semibold">Loan Handover Documents</h5>
                <p class="text-muted small">Download individual loan handover PDFs from the Loans page.</p>
                <a href="{{ route('loans.index') }}" class="btn btn-info text-white">
                    <i class="bi bi-arrow-right me-1"></i> Go to Loans
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
