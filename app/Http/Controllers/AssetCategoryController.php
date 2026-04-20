<?php

namespace App\Http\Controllers;

use App\Models\AssetCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssetCategoryController extends Controller
{
    public function index()
    {
        $categories = AssetCategory::withCount('assets')
            ->latest()
            ->paginate(20);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:asset_categories,name',
            'prefix'      => [
                'required', 'string', 'max:10',
                'regex:/^[A-Za-z]+$/',
                Rule::unique('asset_categories', 'prefix'),
            ],
            'description' => 'nullable|string|max:255',
            'is_active'   => 'boolean',
        ], [
            'prefix.regex' => 'Prefix hanya boleh huruf (A-Z), tanpa angka atau spasi.',
        ]);

        $validated['prefix']    = strtoupper($validated['prefix']);
        $validated['is_active'] = $request->boolean('is_active', true);

        AssetCategory::create($validated);

        return redirect()->route('categories.index')
            ->with('success', "Kategori \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function edit(AssetCategory $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, AssetCategory $category)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100', Rule::unique('asset_categories', 'name')->ignore($category->id)],
            'prefix'      => [
                'required', 'string', 'max:10',
                'regex:/^[A-Za-z]+$/',
                Rule::unique('asset_categories', 'prefix')->ignore($category->id),
            ],
            'description' => 'nullable|string|max:255',
            'is_active'   => 'boolean',
        ], [
            'prefix.regex' => 'Prefix hanya boleh huruf (A-Z), tanpa angka atau spasi.',
        ]);

        $validated['prefix']    = strtoupper($validated['prefix']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', "Kategori \"{$validated['name']}\" berhasil diperbarui.");
    }

    public function destroy(AssetCategory $category)
    {
        // Cegah hapus jika masih ada asset aktif
        if ($category->assets()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', "Kategori \"{$category->name}\" tidak bisa dihapus karena masih memiliki {$category->assets()->count()} asset.");
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', "Kategori \"{$name}\" berhasil dihapus.");
    }

    /**
     * AJAX: generate preview kode untuk prefix tertentu
     */
    public function previewCode(AssetCategory $category)
    {
        return response()->json([
            'code' => $category->generateCode(),
        ]);
    }
}
