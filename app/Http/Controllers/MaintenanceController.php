<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Maintenance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Maintenance::with('asset')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('vendor_name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhereHas('asset', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $maintenances = $query->paginate(15)->withQueryString();
        $totalCost    = Maintenance::where('status', Maintenance::STATUS_COMPLETED)->sum('cost');

        return view('maintenance.index', [
            'maintenances' => $maintenances,
            'statuses'     => Maintenance::statuses(),
            'types'        => Maintenance::types(),
            'totalCost'    => $totalCost,
        ]);
    }

    public function create()
    {
        $assets = Asset::whereIn('status', [Asset::STATUS_AVAILABLE, Asset::STATUS_MAINTENANCE])->orderBy('name')->get();
        return view('maintenance.create', [
            'assets'  => $assets,
            'types'   => Maintenance::types(),
            'statuses'=> Maintenance::statuses(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id'     => 'required|exists:assets,id',
            'type'         => 'required|in:preventive,corrective,inspection',
            'description'  => 'required|string|max:500',
            'vendor_name'  => 'nullable|string|max:255',
            'vendor_phone' => 'nullable|string|max:20',
            'cost'         => 'nullable|numeric|min:0',
            'started_at'   => 'required|date',
            'completed_at' => 'nullable|date|after_or_equal:started_at',
            'status'       => 'required|in:pending,in_progress,completed',
            'notes'        => 'nullable|string',
        ]);

        $maintenance = Maintenance::create($validated);

        // Update asset status based on maintenance status
        if (in_array($validated['status'], [Maintenance::STATUS_PENDING, Maintenance::STATUS_IN_PROGRESS])) {
            $maintenance->asset->update(['status' => Asset::STATUS_MAINTENANCE]);
        } elseif ($validated['status'] === Maintenance::STATUS_COMPLETED) {
            $maintenance->asset->update(['status' => Asset::STATUS_AVAILABLE]);
        }

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance record created successfully.');
    }

    public function show(Maintenance $maintenance)
    {
        $maintenance->load('asset');
        return view('maintenance.show', compact('maintenance'));
    }

    public function edit(Maintenance $maintenance)
    {
        $assets = Asset::orderBy('name')->get();
        return view('maintenance.edit', [
            'maintenance' => $maintenance,
            'assets'      => $assets,
            'types'       => Maintenance::types(),
            'statuses'    => Maintenance::statuses(),
        ]);
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'asset_id'     => 'required|exists:assets,id',
            'type'         => 'required|in:preventive,corrective,inspection',
            'description'  => 'required|string|max:500',
            'vendor_name'  => 'nullable|string|max:255',
            'vendor_phone' => 'nullable|string|max:20',
            'cost'         => 'nullable|numeric|min:0',
            'started_at'   => 'required|date',
            'completed_at' => 'nullable|date|after_or_equal:started_at',
            'status'       => 'required|in:pending,in_progress,completed',
            'notes'        => 'nullable|string',
        ]);

        $oldStatus  = $maintenance->status;
        $maintenance->update($validated);

        // Sync asset status if status changed
        if ($oldStatus !== $validated['status']) {
            if ($validated['status'] === Maintenance::STATUS_COMPLETED) {
                $maintenance->asset->update(['status' => Asset::STATUS_AVAILABLE]);
            } else {
                $maintenance->asset->update(['status' => Asset::STATUS_MAINTENANCE]);
            }
        }

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance record updated successfully.');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance record deleted.');
    }

    public function exportPdf(Request $request)
    {
        $query = Maintenance::with('asset');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $maintenances = $query->latest()->get();
        $totalCost    = $maintenances->where('status', Maintenance::STATUS_COMPLETED)->sum('cost');

        $pdf = Pdf::loadView('maintenance.pdf', compact('maintenances', 'totalCost'));
        return $pdf->download('maintenance-report-' . now()->format('Ymd') . '.pdf');
    }
}
