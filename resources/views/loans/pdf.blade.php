<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Handover Document - Loan #{{ $loan->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #1e3a5f; padding-bottom: 12px; margin-bottom: 20px; }
        .header h2 { color: #1e3a5f; margin: 0 0 4px; font-size: 18px; }
        .header p { margin: 0; color: #666; font-size: 11px; }
        .doc-title { text-align: center; font-size: 14px; font-weight: bold; margin: 16px 0; text-transform: uppercase; letter-spacing: 0.05em; }
        .section { margin-bottom: 16px; }
        .section-title { font-weight: bold; font-size: 11px; text-transform: uppercase; color: #1e3a5f; border-bottom: 1px solid #ddd; padding-bottom: 4px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        table td { padding: 6px 10px; border: 1px solid #ddd; }
        table td:first-child { font-weight: bold; width: 35%; background: #f8f9fa; }
        .signature-section { margin-top: 40px; }
        .sig-grid { display: flex; gap: 20px; justify-content: space-between; }
        .sig-box { flex: 1; text-align: center; }
        .sig-box .sig-line { border-top: 1px solid #333; margin-top: 50px; padding-top: 6px; font-size: 11px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger  { background: #f8d7da; color: #721c24; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>🏢 Asset Management System</h2>
        <p>Official Asset Handover Document</p>
    </div>

    <div class="doc-title">Asset Handover / Return Form</div>

    <div class="section">
        <div class="section-title">Document Information</div>
        <table>
            <tr><td>Document No.</td><td>HDO-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}</td></tr>
            <tr><td>Date Issued</td><td>{{ now()->format('d F Y') }}</td></tr>
            <tr><td>Status</td><td>
                <span class="badge badge-{{ $loan->status_badge }}">{{ $loan->status_label }}</span>
            </td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Asset Information</div>
        <table>
            <tr><td>Asset Name</td><td>{{ $loan->asset->name }}</td></tr>
            <tr><td>Asset Code</td><td>{{ $loan->asset->code }}</td></tr>
            <tr><td>Category</td><td>{{ $loan->asset->category }}</td></tr>
            <tr><td>Brand / Model</td><td>{{ $loan->asset->brand }} {{ $loan->asset->model }}</td></tr>
            <tr><td>Serial Number</td><td>{{ $loan->asset->serial_number ?: '-' }}</td></tr>
            <tr><td>Condition (Before Loan)</td><td>{{ ucfirst($loan->condition_before) }}</td></tr>
            @if($loan->condition_after)
            <tr><td>Condition (After Return)</td><td>{{ ucfirst($loan->condition_after) }}</td></tr>
            @endif
        </table>
    </div>

    <div class="section">
        <div class="section-title">Borrower Information</div>
        <table>
            <tr><td>Borrower Name</td><td>{{ $loan->borrower_name }}</td></tr>
            <tr><td>Department</td><td>{{ $loan->borrower_department }}</td></tr>
            <tr><td>Phone</td><td>{{ $loan->borrower_phone ?: '-' }}</td></tr>
            <tr><td>Purpose</td><td>{{ $loan->purpose }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Loan Period</div>
        <table>
            <tr><td>Borrowed At</td><td>{{ $loan->borrowed_at->format('d F Y, H:i') }}</td></tr>
            <tr><td>Expected Return</td><td>{{ $loan->expected_return_at?->format('d F Y, H:i') ?? '-' }}</td></tr>
            <tr><td>Actual Return</td><td>{{ $loan->returned_at?->format('d F Y, H:i') ?? 'Not yet returned' }}</td></tr>
        </table>
    </div>

    @if($loan->notes)
    <div class="section">
        <div class="section-title">Notes</div>
        <p>{{ $loan->notes }}</p>
    </div>
    @endif

    <div class="signature-section">
        <div class="section-title">Signatures</div>
        <table style="border:none">
            <tr>
                <td style="border:none; text-align:center; padding: 0 10px;">
                    <div style="margin-top: 60px; border-top: 1px solid #333; padding-top: 6px; font-size: 11px;">
                        <strong>Handover By</strong><br>
                        (IT Support)<br>
                        Muhamad Ariel Saputra
                    </div>
                </td>
                <td style="border:none; text-align:center; padding: 0 10px;">
                    <div style="margin-top: 60px; border-top: 1px solid #333; padding-top: 6px; font-size: 11px;">
                        <strong>Received By</strong><br>
                        (Borrower)<br>
                        {{ $loan->borrower_name }}
                    </div>
                </td>
                @if($loan->returned_at)
                <td style="border:none; text-align:center; padding: 0 10px;">
                    <div style="margin-top: 60px; border-top: 1px solid #333; padding-top: 6px; font-size: 11px;">
                        <strong>Return Verified By</strong><br>
                        (IT Support)<br>
                        Muhamad Ariel Saputra
                    </div>
                </td>
                @endif
            </tr>
        </table>
    </div>

    <div class="footer">
        Generated by Asset Management System &mdash; {{ isset($now) ? $now->format('d M Y H:i') : now()->setTimezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
    </div>
</body>
</html>
