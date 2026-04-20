<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Maintenance Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #1e3a5f; padding-bottom: 12px; margin-bottom: 20px; }
        .header h2 { color: #1e3a5f; margin: 0 0 4px; font-size: 18px; }
        .header p { margin: 0; color: #666; font-size: 10px; }
        .doc-title { text-align: center; font-size: 13px; font-weight: bold; margin: 16px 0; text-transform: uppercase; letter-spacing: .05em; }
        .meta { margin-bottom: 16px; font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        thead th { background: #1e3a5f; color: #fff; padding: 7px 8px; text-align: left; font-size: 10px; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 10px; }
        tbody tr:nth-child(even) td { background: #f8f9fa; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-primary { background: #cce5ff; color: #004085; }
        .summary { background: #f0f4ff; border: 1px solid #c8d8ff; padding: 12px 16px; border-radius: 6px; margin-top: 16px; }
        .summary strong { color: #1e3a5f; }
        .footer { margin-top: 24px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>🏢 Asset Management System</h2>
        <p>Maintenance Activity Report</p>
    </div>
    <div class="doc-title">Maintenance Report</div>
    <div class="meta">
        Report Date: {{ now()->setTimezone('Asia/Jakarta')->format('d F Y, H:i') }} WIB &nbsp;|&nbsp; Total Records: {{ $maintenances->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Asset</th>
                <th>Type</th>
                <th>Description</th>
                <th>Vendor</th>
                <th>Cost (Rp)</th>
                <th>Started</th>
                <th>Completed</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($maintenances as $i => $m)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $m->asset->name ?? '-' }}</strong><br>{{ $m->asset->code ?? '' }}</td>
                <td>{{ ($m->type_label ?? ucfirst($m->type)) }}</td>
                <td>{{ Str::limit($m->description, 35) }}</td>
                <td>{{ $m->vendor_name ?: '-' }}</td>
                <td>{{ $m->cost ? number_format($m->cost, 0, ',', '.') : '-' }}</td>
                <td>{{ $m->started_at->format('d/m/Y') }}</td>
                <td>{{ $m->completed_at?->format('d/m/Y') ?? '-' }}</td>
                <td>
                    <span class="badge badge-{{ $m->status_badge }}">{{ ucwords(str_replace('_',' ',$m->status)) }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center; color:#999; padding:20px">No records found</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <strong>Cost Summary</strong><br><br>
        Total Maintenance Records: {{ $maintenances->count() }}<br>
        Completed: {{ $maintenances->where('status','completed')->count() }}<br>
        In Progress: {{ $maintenances->where('status','in_progress')->count() }}<br>
        Pending: {{ $maintenances->where('status','pending')->count() }}<br><br>
        <strong>Total Cost (Completed): Rp {{ number_format($totalCost, 0, ',', '.') }}</strong>
    </div>

    <div class="footer">
        Dicetak oleh Asset Management System &mdash; {{ now()->setTimezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
    </div>
</body>
</html>
