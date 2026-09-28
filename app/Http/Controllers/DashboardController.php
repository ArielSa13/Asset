<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Loan;
use App\Models\Maintenance;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stat utama ──
        $totalAssets       = Asset::count();
        $availableAssets   = Asset::where('status', 'available')->count();
        $inUseAssets       = Asset::where('status', 'in_use')->count();
        $maintenanceAssets = Asset::where('status', 'maintenance')->count();
        $retiredAssets     = Asset::where('status', 'retired')->count();

        // ── Kondisi asset ──
        $goodAssets   = Asset::where('condition', 'good')->count();
        $fairAssets   = Asset::where('condition', 'fair')->count();
        $poorAssets   = Asset::where('condition', 'poor')->count();
        $brokenAssets = Asset::where('condition', 'broken')->count();

        // ── Loan stats ──
        $activeLoans  = Loan::whereNull('returned_at')->count();
        $overdueLoans = Loan::whereNull('returned_at')
            ->where('expected_return_at', '<', now())
            ->count();
        $returnedThisMonth = Loan::whereNotNull('returned_at')
            ->whereMonth('returned_at', now()->month)
            ->count();

        // ── Maintenance stats ──
        $pendingMaintenanceCount  = Maintenance::where('status', 'pending')->count();
        $ongoingMaintenanceCount  = Maintenance::where('status', 'in_progress')->count();

        // ── Asset per kategori (pakai relasi kategori baru) ──
        $assetsByCategory = AssetCategory::withCount('assets')
            ->having('assets_count', '>', 0)
            ->orderByDesc('assets_count')
            ->get();

        // ── Asset per kondisi untuk chart ──
        $assetsByCondition = Asset::select('condition', DB::raw('COUNT(*) as total'))
            ->groupBy('condition')
            ->pluck('total', 'condition');

        // ── Top kategori terbanyak ──
        $topCategories = AssetCategory::withCount([
            'assets',
            'assets as available_count' => fn($q) => $q->where('status', 'available'),
            'assets as in_use_count'    => fn($q) => $q->where('status', 'in_use'),
        ])
            ->having('assets_count', '>', 0)
            ->orderByDesc('assets_count')
            ->take(6)
            ->get();

        // ── Loan aktif + overdue ──
        $activeLoansDetail = Loan::with('asset')
            ->whereNull('returned_at')
            ->latest('borrowed_at')
            ->take(6)
            ->get();

        $overdueLoansDetail = Loan::with('asset')
            ->whereNull('returned_at')
            ->where('expected_return_at', '<', now())
            ->orderBy('expected_return_at')
            ->take(5)
            ->get();

        // ── Pending maintenance ──
        $pendingMaintenance = Maintenance::with('asset')
            ->whereIn('status', ['pending', 'in_progress'])
            ->latest()
            ->take(5)
            ->get();

        // ── Asset terbaru ditambahkan ──
        $recentAssets = Asset::with('assetCategory')
            ->latest()
            ->take(5)
            ->get();

        // ── Nilai total asset (harga pembelian) ──
        $totalAssetValue = Asset::sum('purchase_price');

        return view('dashboard.index', compact(
            'totalAssets',
            'availableAssets',
            'inUseAssets',
            'maintenanceAssets',
            'retiredAssets',
            'goodAssets',
            'fairAssets',
            'poorAssets',
            'brokenAssets',
            'activeLoans',
            'overdueLoans',
            'returnedThisMonth',
            'pendingMaintenanceCount',
            'ongoingMaintenanceCount',
            'assetsByCategory',
            'assetsByCondition',
            'topCategories',
            'activeLoansDetail',
            'overdueLoansDetail',
            'pendingMaintenance',
            'recentAssets',
            'totalAssetValue',
        ));
    }
}
