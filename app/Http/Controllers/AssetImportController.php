<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AssetImportController extends Controller
{
    public function index()
    {
        return view('assets.import');
    }

    public function downloadTemplate()
    {
        $path = storage_path('app/templates/template-import-asset.xlsx');
        return response()->download($path, 'template-import-asset.xlsx');
    }

    /**
     * PREVIEW IMPORT
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $rows = $this->readFile($request->file('file'));

        // 🔥 kategori jadi lowercase biar aman
        $categories = AssetCategory::active()
            ->get()
            ->mapWithKeys(fn($c) => [strtolower($c->name) => $c->id])
            ->toArray();

        // 🔥 mapping keyword → kategori DB
        $categoryMap = [
            'laptop'   => 'Laptop / PC',
            'notebook' => 'Laptop / PC',
            'macbook'  => 'Laptop / PC',

            'adapter'  => 'Perangkat Lainnya',

            'camera'   => 'Kamera / CCTV',
            'cctv'     => 'Kamera / CCTV',

            'printer'  => 'Printer',
            'monitor'  => 'Monitor',
            'access point' => 'Access Point',
            'ups' => 'UPS',
        ];

        $results = [];

        foreach ($rows as $i => $row) {
            $rowNum = $i + 1;
            $errors = [];

            $name         = trim($row[0] ?? '');
            $code         = strtoupper(trim($row[1] ?? ''));
            $categoryName = trim($row[2] ?? '');
            $brand        = trim($row[3] ?? '');
            $model        = trim($row[4] ?? '');
            $serialNumber = trim($row[5] ?? '');

            // 🔥 mapping kondisi Indonesia
            $conditionRaw = strtolower(trim($row[6] ?? ''));
            $conditionMap = [
                'baik'  => 'good',
                'rusak' => 'broken',
            ];
            $condition = $conditionMap[$conditionRaw] ?? $conditionRaw;

            $status   = strtolower(trim($row[7] ?? ''));
            $location = trim($row[8] ?? '');

            // =========================
            // 🔥 AUTO MAPPING KATEGORI
            // =========================
            $key = strtolower($categoryName);
            $categoryId = null;

            foreach ($categoryMap as $keyword => $mappedCategory) {
                if (str_contains($key, $keyword)) {
                    $categoryId = $categories[strtolower($mappedCategory)] ?? null;
                    break;
                }
            }

            // fallback ke "Perangkat Lainnya"
            if (!$categoryId) {
                $categoryId = $categories['perangkat lainnya'] ?? null;
            }

            // =========================
            // VALIDASI
            // =========================
            if (!$name)         $errors[] = 'Nama asset kosong';
            if (!$code)         $errors[] = 'Kode asset kosong';
            if (!$categoryId)   $errors[] = 'Kategori tidak ditemukan';
            if (!$condition)    $errors[] = 'Kondisi kosong';
            if (!$status)       $errors[] = 'Status kosong';

            if ($condition && !in_array($condition, ['good','fair','poor','broken']))
                $errors[] = "Kondisi \"$conditionRaw\" tidak valid";

            if ($status && !in_array($status, ['available','in_use','maintenance','retired']))
                $errors[] = "Status \"$status\" tidak valid";

            // duplikat file
            $codesInFile = array_column($results, 'code');
            if ($code && in_array($code, $codesInFile))
                $errors[] = "Kode \"$code\" duplikat dalam file";

            // duplikat DB
            if ($code && Asset::withTrashed()->where('code', $code)->exists())
                $errors[] = "Kode \"$code\" sudah ada di database";

            $results[] = [
                'row'           => $rowNum,
                'name'          => $name,
                'code'          => $code,
                'category_name' => $categoryName,
                'category_id'   => $categoryId,
                'brand'         => $brand,
                'model'         => $model,
                'serial_number' => $serialNumber,
                'condition'     => $condition,
                'status'        => $status,
                'location'      => $location,
                'errors'        => $errors,
                'valid'         => empty($errors),
            ];
        }

        $validCount   = count(array_filter($results, fn($r) => $r['valid']));
        $invalidCount = count($results) - $validCount;

        session(['import_data' => $results]);

        return view('assets.import', compact('results', 'validCount', 'invalidCount'));
    }

    /**
     * IMPORT DATA
     */
    public function import(Request $request)
    {
        $results = session('import_data', []);

        if (empty($results)) {
            return redirect()->route('assets.import')
                ->with('error', 'Data tidak ditemukan.');
        }

        $validRows = array_filter($results, fn($r) => $r['valid']);

        if (empty($validRows)) {
            return redirect()->route('assets.import')
                ->with('error', 'Tidak ada data valid.');
        }

        $imported = 0;

        DB::beginTransaction();
        try {
            foreach ($validRows as $row) {
                Asset::create([
                    'name'          => $row['name'],
                    'code'          => $row['code'],
                    'category_id'   => $row['category_id'],
                    'category'      => $row['category_name'],
                    'brand'         => $row['brand'] ?: null,
                    'model'         => $row['model'] ?: null,
                    'serial_number' => $row['serial_number'] ?: null,
                    'condition'     => $row['condition'],
                    'status'        => $row['status'],
                    'location'      => $row['location'] ?: null,
                    'original_location' => $row['location'] ?: null,
                ]);

                $imported++;
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('assets.import')
                ->with('error', 'Gagal import: ' . $e->getMessage());
        }

        session()->forget('import_data');

        return redirect()->route('assets.index')
            ->with('success', "$imported asset berhasil diimport.");
    }

    /**
     * READ FILE (HEADER FLEXIBLE)
     */
    private function readFile($file): array
    {
        $ext  = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();
        $rows = [];

        if ($ext === 'csv') {
            $handle = fopen($path, 'r');
            $isFirst = true;

            while (($line = fgetcsv($handle)) !== false) {
                if ($isFirst) { $isFirst = false; continue; }
                if (array_filter($line)) $rows[] = $line;
            }

            fclose($handle);

        } else {
            $spreadsheet = IOFactory::load($path);
            $sheet       = $spreadsheet->getActiveSheet();
            $firstData   = false;

            foreach ($sheet->getRowIterator() as $rowObj) {
                $cells = [];

                foreach ($rowObj->getCellIterator() as $cell) {
                    $cells[] = $cell->getFormattedValue();
                }

                $first = strtolower(trim($cells[0] ?? ''));

                // 🔥 header fleksibel
                if (!$firstData) {
                    if (str_contains($first, 'nama')) {
                        $firstData = true;
                        continue;
                    }
                    continue;
                }

                if (!$first) continue;

                $rows[] = $cells;
            }
        }

        return array_slice($rows, 0, 1000);
    }
}