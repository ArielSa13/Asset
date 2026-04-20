<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rekap Peminjaman Aset</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 16px; }
        .header { text-align: center; border-bottom: 2px solid #1e3a5f; padding-bottom: 10px; margin-bottom: 14px; }
        .header h2 { color: #1e3a5f; margin: 0 0 3px; font-size: 17px; }
        .header p  { margin: 0; color: #666; font-size: 10px; }
        .doc-title { text-align: center; font-size: 13px; font-weight: bold; margin: 12px 0; text-transform: uppercase; letter-spacing: .05em; }
        .meta { font-size: 10px; color: #666; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        thead th { background: #1e3a5f; color: #fff; padding: 6px 7px; font-size: 10px; text-align: left; }
        tbody td { padding: 5px 7px; border-bottom: 1px solid #eee; font-size: 10px; vertical-align: top; }
        tbody tr:nth-child(even) td { background: #f8f9fa; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .b-success  { background: #d4edda; color: #155724; }
        .b-primary  { background: #cce5ff; color: #004085; }
        .b-danger   { background: #f8d7da; color: #721c24; }
        .summary { background: #eef2ff; border: 1px solid #c7d2fe; padding: 10px 14px; border-radius: 6px; margin-top: 14px; font-size: 10px; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>🏢 Asset Management System</h2>
        <p>Rekap Data Peminjaman Aset</p>
    </div>
    <div class="doc-title">Rekap Peminjaman Aset</div>
    <div class="meta">
        Tanggal Cetak: {{ $now->translatedFormat('d F Y, H:i') }} WIB &nbsp;|&nbsp;
        Total: {{ $loans->count() }} peminjaman
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Aset</th>
                <th>Kode</th>
                <th>Peminjam</th>
                <th>Departemen</th>
                <th>Tujuan</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali (Rencana)</th>
                <th>Tgl Kembali (Aktual)</th>
                <th>Kondisi Sebelum</th>
                <th>Kondisi Sesudah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $i => $loan)
            @php
                $statusLabel = $loan->status_label;
                $statusClass = $loan->status_badge === 'success' ? 'b-success' : ($loan->status_badge === 'danger' ? 'b-danger' : 'b-primary');
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $loan->asset->name ?? '-' }}</strong></td>
                <td>{{ $loan->asset->code ?? '-' }}</td>
                <td>{{ $loan->borrower_name }}</td>
                <td>{{ $loan->borrower_department }}</td>
                <td>{{ Str::limit($loan->purpose, 30) }}</td>
                <td>{{ $loan->borrowed_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}</td>
                <td>{{ $loan->expected_return_at?->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') ?? '-' }}</td>
                <td>{{ $loan->returned_at?->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') ?? '-' }}</td>
                <td>{{ ucfirst($loan->condition_before) }}</td>
                <td>{{ $loan->condition_after ? ucfirst($loan->condition_after) : '-' }}</td>
                <td><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
            </tr>
            @empty
            <tr><td colspan="12" style="text-align:center;color:#999;padding:16px">Tidak ada data peminjaman</td></tr>
            @endforelse
        </tbody>
    </table>

    @php
        $active   = $loans->filter(fn($l) => !$l->isReturned() && !$l->isOverdue())->count();
        $overdue  = $loans->filter(fn($l) => $l->isOverdue())->count();
        $returned = $loans->filter(fn($l) => $l->isReturned())->count();
    @endphp
    <div class="summary">
        <strong>Ringkasan Peminjaman</strong><br><br>
        Total: {{ $loans->count() }} &nbsp;|&nbsp;
        Aktif: {{ $active }} &nbsp;|&nbsp;
        Overdue: {{ $overdue }} &nbsp;|&nbsp;
        Dikembalikan: {{ $returned }}
    </div>

    <div class="footer">
        Dicetak oleh Asset Management System &mdash; {{ $now->format('d M Y H:i') }} WIB
    </div>
</body>
</html>
