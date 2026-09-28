<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with('assetCategory');

        // 🔍 SEARCH
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('code', 'like', "%{$request->search}%")
                    ->orWhere('serial_number', 'like', "%{$request->search}%");
            });
        }

        // 📂 FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🔥 FILTER CONDITION (INI YANG KAMU TAMBAHIN)
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // 🔥 JUMLAH DATA
        $perPage = $request->get('per_page', 15);

        if ($perPage === 'all') {
            $assets = $query->orderBy('code', 'asc')->get();
        } else {
            $assets = $query->orderBy('code', 'asc')
                ->paginate($perPage)
                ->withQueryString();
        }

        return view('assets.index', [
            'assets'     => $assets,
            'statuses'   => Asset::statuses(),
            'conditions' => Asset::conditions(),
        ]);
    }

    public function create()
    {
        $categories = AssetCategory::active()->orderBy('name')->get();

        if ($categories->isEmpty()) {
            return redirect()->route('categories.create')
                ->with('info', 'Tambahkan minimal satu kategori sebelum menambah asset.');
        }

        return view('assets.create', [
            'categories' => $categories,
            'statuses'   => Asset::statuses(),
            'conditions' => Asset::conditions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'code'           => [
                'required',
                'string',
                'max:100',
                Rule::unique('assets', 'code')->whereNull('deleted_at'),
            ],
            'category_id'    => 'required|exists:asset_categories,id',
            'brand'          => 'nullable|string|max:100',
            'model'          => 'nullable|string|max:100',
            'serial_number'  => 'nullable|string|max:100',
            'purchase_date'  => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'condition'      => 'required|in:good,fair,poor,broken',
            'status'         => 'required|in:available,in_use,maintenance,retired',
            'description'    => 'nullable|string',
            'location'       => 'nullable|string|max:255',
            'original_location' => 'nullable|string|max:255',
            'image'          => 'nullable|image|max:2048',
        ]);

        if (!empty($validated['location']) && empty($validated['original_location'])) {
            $validated['original_location'] = $validated['location'];
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('assets', 'public');
        }

        Asset::create($validated);

        return redirect()->route('assets.index')
            ->with('success', 'Asset berhasil ditambahkan.');
    }

    public function show(Asset $asset)
    {
        $asset->load([
            'assetCategory',
            'loans'        => fn($q) => $q->latest()->take(10),
            'maintenances' => fn($q) => $q->latest()->take(10),
        ]);

        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $categories = AssetCategory::active()->orderBy('name')->get();

        return view('assets.edit', [
            'asset'      => $asset,
            'categories' => $categories,
            'statuses'   => Asset::statuses(),
            'conditions' => Asset::conditions(),
        ]);
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'code'           => [
                'required',
                'string',
                'max:100',
                Rule::unique('assets', 'code')->ignore($asset->id)->whereNull('deleted_at'),
            ],
            'category_id'    => 'required|exists:asset_categories,id',
            'brand'          => 'nullable|string|max:100',
            'model'          => 'nullable|string|max:100',
            'serial_number'  => 'nullable|string|max:100',
            'purchase_date'  => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'condition'      => 'required|in:good,fair,poor,broken',
            'status'         => 'required|in:available,in_use,maintenance,retired',
            'description'    => 'nullable|string',
            'location'       => 'nullable|string|max:255',
            'original_location' => 'nullable|string|max:255',
            'image'          => 'nullable|image|max:2048',
        ]);

        if ($validated['status'] !== 'in_use' && empty($validated['location'])) {
            $validated['location'] = $asset->original_location;
        }

        if ($request->hasFile('image')) {
            if ($asset->image) Storage::disk('public')->delete($asset->image);
            $validated['image'] = $request->file('image')->store('assets', 'public');
        }

        $asset->update($validated);

        return redirect()->route('assets.index')
            ->with('success', 'Asset berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
    {
        if ($asset->image) Storage::disk('public')->delete($asset->image);
        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'Asset berhasil dihapus.');
    }

    public function generateCode(Request $request)
    {
        $request->validate(['category_id' => 'required|exists:asset_categories,id']);
        $category = AssetCategory::findOrFail($request->category_id);

        return response()->json([
            'code'   => $category->generateCode(),
            'prefix' => $category->prefix,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = Asset::with('assetCategory');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $assets = $query->orderBy('code')->get();
        $now    = now();

        $pdf = Pdf::loadView('assets.pdf', compact('assets', 'now'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('asset-list-' . $now->format('Ymd') . '.pdf');
    }
}
