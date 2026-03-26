<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductImportController extends Controller
{
    public function show()
    {
        return view('products.import');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->store('temp');

        // Reading data for preview
        $rawData = Excel::toArray(new ProductImport, $path);
        $rows = $rawData[0] ?? [];

        if (empty($rows)) {
            return back()->with('error', 'El archivo está vacío o tiene un formato incorrecto.');
        }

        return view('products.import-preview', [
            'rows' => $rows,
            'path' => $path,
        ]);
    }

    public function store(Request $request)
    {
        $path = $request->input('path');

        if (! Storage::exists($path)) {
            return redirect()->route('products.import')->with('error', 'El archivo temporal ha expirado. Por favor sube el archivo de nuevo.');
        }

        try {
            Excel::import(new ProductImport, $path);
            Storage::delete($path);

            return redirect()->route('products.index')->with('success', 'Productos importados correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el archivo: '.$e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Nombre producto',
            'descripcion',
            'precio compra',
            'precio venta',
            'cantidad',
        ];

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=plantilla_productos.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }
}
