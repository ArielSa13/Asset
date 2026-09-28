@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 220px;
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .cat-bar {
            height: 8px;
            border-radius: 4px;
            background: #e9ecef;
            overflow: hidden;
        }

        .cat-bar-fill {
            height: 100%;
            border-radius: 4px;
            transition: width .6s ease;
        }

        .overdue-badge {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .6
            }
        }

        .summary-pill {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .3rem .75rem;
            border-radius: 20px;
            font-size: .8rem;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')

    {{-- ── Baris 1: Stat Cards Utama ── --}}
    <div class="row g-3 mb-3">
        {{-- Total Asset --}}
        <div class="col-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-pc-display"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="fs-3 fw-bold text-primary lh-1">{{ $totalAssets }}</div>
                        <div class="text-muted small mt-1">Total Asset</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Available --}}
        <div class="col-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="fs-3 fw-bold text-success lh-1">{{ $availableAssets }}</div>
                        <div class="text-muted small mt-1">Tersedia</div>
                        <div class="text-muted" style="font-size:.72rem">
                            {{ $totalAssets > 0 ? round(($availableAssets / $totalAssets) * 100) : 0 }}% dari total
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Loans --}}
        <div class="col-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 {{ $overdueLoans > 0 ? 'border-danger border-2' : '' }}">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <div>
                        <div class="fs-3 fw-bold text-info lh-1">{{ $activeLoans }}</div>
                        <div class="text-muted small mt-1">Dipinjam</div>
                        @if ($overdueLoans > 0)
                            <div class="overdue-badge" style="font-size:.72rem">
                                <span class="badge bg-danger">{{ $overdueLoans }} overdue</span>
                            </div>
                        @else
                            <div class="text-muted" style="font-size:.72rem">Semua on-time</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Maintenance --}}
        <div class="col-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div>
                        <div class="fs-3 fw-bold text-warning lh-1">{{ $maintenanceAssets }}</div>
                        <div class="text-muted small mt-1">Maintenance</div>
                        <div class="text-muted" style="font-size:.72rem">
                            {{ $pendingMaintenanceCount }} pending · {{ $ongoingMaintenanceCount }} ongoing
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Baris 2: Status Bar + Kondisi Asset ── --}}
    <div class="row g-3 mb-3">
        {{-- Status Overview --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Status Asset</h6>
                    @php $total = max($totalAssets, 1); @endphp

                    @foreach ([['label' => 'Available', 'count' => $availableAssets, 'color' => '#28a745', 'bg' => 'bg-success'], ['label' => 'Dipinjam', 'count' => $inUseAssets, 'color' => '#2563eb', 'bg' => 'bg-primary'], ['label' => 'Maintenance', 'count' => $maintenanceAssets, 'color' => '#ffc107', 'bg' => 'bg-warning'], ['label' => 'Retired', 'count' => $retiredAssets, 'color' => '#6c757d', 'bg' => 'bg-secondary']] as $s)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small fw-semibold">{{ $s['label'] }}</span>
                                <span class="small text-muted">{{ $s['count'] }} asset
                                    ({{ round(($s['count'] / $total) * 100) }}%)</span>
                            </div>
                            <div class="cat-bar">
                                <div class="cat-bar-fill"
                                    style="width:{{ ($s['count'] / $total) * 100 }}%;background:{{ $s['color'] }}"></div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Kondisi pills --}}
                    <hr class="my-3">
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="summary-pill bg-success bg-opacity-10 text-success">
                            <i class="bi bi-circle-fill" style="font-size:.5rem"></i> Good: {{ $goodAssets }}
                        </span>
                        <span class="summary-pill bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-circle-fill" style="font-size:.5rem"></i> Fair: {{ $fairAssets }}
                        </span>
                        <span class="summary-pill bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-circle-fill" style="font-size:.5rem"></i> Poor: {{ $poorAssets }}
                        </span>
                        <span class="summary-pill bg-dark bg-opacity-10 text-dark">
                            <i class="bi bi-circle-fill" style="font-size:.5rem"></i> Broken: {{ $brokenAssets }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart by Category --}}
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-semibold mb-2">Asset per Kategori</h6>
                    <div class="chart-container">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Baris 3: Top Kategori ── --}}
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Ringkasan per Kategori</span>
            <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary">Kelola Kategori</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="font-size:.85rem">
                <thead class="table-light">
                    <tr>
                        <th>Kategori</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Tersedia</th>
                        <th class="text-center">Dipinjam</th>
                        <th style="width:180px">Proporsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topCategories as $cat)
                        @php $pct = $totalAssets > 0 ? ($cat->assets_count/$totalAssets)*100 : 0; @endphp
                        <tr>
                            <td>
                                <span class="fw-semibold">{{ $cat->name }}</span>
                                <code class="ms-1 text-muted" style="font-size:.75rem">{{ $cat->prefix }}-xxx</code>
                            </td>
                            <td class="text-center"><strong>{{ $cat->assets_count }}</strong></td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-75">{{ $cat->available_count }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary bg-opacity-75">{{ $cat->in_use_count }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="cat-bar flex-grow-1">
                                        <div class="cat-bar-fill bg-primary" style="width:{{ $pct }}%"></div>
                                    </div>
                                    <span class="text-muted"
                                        style="font-size:.75rem;width:32px">{{ round($pct) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Belum ada kategori</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Baris 4: Loan Aktif + Overdue ── --}}
    <div class="row g-3 mb-3">
        {{-- Loan Aktif --}}
        <div class="col-lg-{{ $overdueLoans > 0 ? '6' : '12' }}">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">
                        <i class="bi bi-arrow-left-right me-1 text-info"></i> Loan Aktif
                        <span class="badge bg-info ms-1">{{ $activeLoans }}</span>
                    </span>
                    <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-info">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" style="font-size:.83rem">
                        <thead class="table-light">
                            <tr>
                                <th>Asset</th>
                                <th>Peminjam</th>
                                <th>Departemen</th>
                                <th>Dipinjam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeLoansDetail as $loan)
                                <tr>
                                    <td>
                                        <a href="{{ route('loans.show', $loan) }}"
                                            class="text-decoration-none text-dark fw-semibold">
                                            {{ Str::limit($loan->asset->name ?? '-', 25) }}
                                        </a>
                                    </td>
                                    <td>{{ $loan->borrower_name }}</td>
                                    <td class="text-muted">{{ $loan->borrower_department ?? '—' }}</td>
                                    <td class="text-muted">{{ $loan->borrowed_at?->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        <i class="bi bi-inbox me-1"></i> Tidak ada loan aktif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Overdue (hanya tampil kalau ada) --}}
        @if ($overdueLoans > 0)
            <div class="col-lg-6">
                <div class="card shadow-sm h-100 border-danger border-2">
                    <div class="card-header bg-danger bg-opacity-10 d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-danger">
                            <i class="bi bi-exclamation-triangle me-1"></i> Overdue
                            <span class="badge bg-danger ms-1">{{ $overdueLoans }}</span>
                        </span>
                        <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-danger">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle" style="font-size:.83rem">
                            <thead class="table-light">
                                <tr>
                                    <th>Asset</th>
                                    <th>Peminjam</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Telat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($overdueLoansDetail as $loan)
                                    @php $days = now()->diffInDays($loan->expected_return_at); @endphp
                                    <tr class="table-danger">
                                        <td class="fw-semibold">{{ Str::limit($loan->asset->name ?? '-', 22) }}</td>
                                        <td>{{ $loan->borrower_name }}</td>
                                        <td>{{ $loan->expected_return_at?->format('d M Y') }}</td>
                                        <td><span class="badge bg-danger">{{ $days }} hari</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- ── Baris 5: Maintenance + Asset Terbaru ── --}}
    <div class="row g-3">
        {{-- Pending Maintenance --}}
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">
                        <i class="bi bi-tools me-1 text-warning"></i> Maintenance Aktif
                        @if ($pendingMaintenanceCount + $ongoingMaintenanceCount > 0)
                            <span
                                class="badge bg-warning text-dark ms-1">{{ $pendingMaintenanceCount + $ongoingMaintenanceCount }}</span>
                        @endif
                    </span>
                    <a href="{{ route('maintenance.index') }}" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" style="font-size:.83rem">
                        <thead class="table-light">
                            <tr>
                                <th>Asset</th>
                                <th>Tipe</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingMaintenance as $m)
                                <tr>
                                    <td class="fw-semibold">{{ Str::limit($m->asset->name ?? '-', 25) }}</td>
                                    <td class="text-muted">{{ $m->type_label ?? ucfirst($m->type) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $m->status_badge }}">
                                            {{ ucwords(str_replace('_', ' ', $m->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        <i class="bi bi-check-circle me-1 text-success"></i> Tidak ada maintenance aktif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Asset Terbaru --}}
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">
                        <i class="bi bi-plus-circle me-1 text-success"></i> Asset Terbaru
                    </span>
                    <a href="{{ route('assets.index') }}" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" style="font-size:.83rem">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Kode</th>
                                <th>Kategori</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAssets as $asset)
                                <tr>
                                    <td>
                                        <a href="{{ route('assets.show', $asset) }}"
                                            class="text-decoration-none text-dark fw-semibold">
                                            {{ Str::limit($asset->name, 22) }}
                                        </a>
                                    </td>
                                    <td><code style="font-size:.78rem">{{ $asset->code }}</code></td>
                                    <td class="text-muted">{{ $asset->assetCategory->name ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $asset->status_badge }}">
                                            {{ ucwords(str_replace('_', ' ', $asset->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Belum ada asset</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($assetsByCategory->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($assetsByCategory->pluck('assets_count')) !!},
                    backgroundColor: [
                        '#2563eb', '#28a745', '#ffc107', '#dc3545',
                        '#6f42c1', '#17a2b8', '#fd7e14', '#20c997', '#e83e8c'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 11
                            },
                            boxWidth: 12,
                            padding: 10
                        }
                    }
                },
                cutout: '60%',
            }
        });
    </script>
@endpush
