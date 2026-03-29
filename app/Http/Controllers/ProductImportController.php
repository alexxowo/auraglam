<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ProductImportController extends Controller
{
    public function show()
    {
        return view('products.import');
    }

    private function getColumnMappings(Request $request): array
    {
        $mapping = [];
        $fields = [
            'name' => $request->input('col_name', 'A'),
            'description' => $request->input('col_description', 'B'),
            'category' => $request->input('col_category', 'C'),
            'purchase_price' => $request->input('col_purchase_price', 'D'),
            'selling_price' => $request->input('col_selling_price', 'E'),
            'stock' => $request->input('col_stock', 'F'),
        ];

        foreach ($fields as $field => $letter) {
            if ($letter) {
                // Remove spaces and make uppercase
                $letter = strtoupper(trim($letter));
                // Convert to 0-based index
                $mapping[$field] = Coordinate::columnIndexFromString($letter) - 1;
            }
        }

        return $mapping;
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            'col_name' => 'required|string',
            'col_purchase_price' => 'required|string',
            'col_selling_price' => 'required|string',
            'col_stock' => 'required|string',
        ]);

        $file = $request->file('file');
        $path = $file->store('temp');

        $mapping = $this->getColumnMappings($request);

        // Reading data for preview
        $rawData = Excel::toArray(new ProductImport($mapping), $path);

        // Skip first row (headers) and get the next 10 for preview
        $rows = array_slice($rawData[0] ?? [], 1, 10);

        if (empty($rows)) {
            return back()->with('error', 'El archivo está vacío o los datos no superan la fila de cabecera.');
        }

        return view('products.import-preview', [
            'rows' => $rows,
            'mapping' => $mapping,
            'path' => $path,
            'config' => json_encode($mapping),
        ]);
    }

    public function store(Request $request)
    {
        $path = $request->input('path');
        $mapping = json_decode($request->input('config'), true);

        if (! Storage::exists($path)) {
            return redirect()->route('products.import')->with('error', 'El archivo temporal ha expirado. Por favor sube el archivo de nuevo.');
        }

        try {
            Excel::import(new ProductImport($mapping), $path);
            Storage::delete($path);

            return redirect()->route('products.index')->with('success', 'Productos importados correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('products.import')->with('error', 'Error al procesar el archivo: '.$e->getMessage());
        }
    }

    public function downloadTemplate(Request $request)
    {
        $mapping = $this->getColumnMappings($request);

        $labels = [
            'name' => 'Nombre_Producto',
            'description' => 'Descripcion',
            'category' => 'Slug_Categoria',
            'purchase_price' => 'Precio_Compra',
            'selling_price' => 'Precio_Venta',
            'stock' => 'Stock',
        ];

        $maxIndex = empty($mapping) ? 0 : max($mapping);
        $headers = array_fill(0, $maxIndex + 1, '');

        foreach ($mapping as $field => $index) {
            $headers[$index] = $labels[$field] ?? $field;
        }

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');
            // Adding BOM for excel UTF-8 compatibility
            fwrite($file, $bom = (chr(0xEF).chr(0xBB).chr(0xBF)));
            fputcsv($file, $headers);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename=plantilla_personalizada_productos.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }
}
