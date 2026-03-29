<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        return Product::query()->with('category');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Descripción',
            'Categoría',
            'Precio Compra',
            'Precio Venta',
            'Stock',
            'Creado en',
        ];
    }

    /**
    * @var Product $product
    */
    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->description,
            $product->category ? $product->category->name : 'N/A',
            $product->purchase_price,
            $product->selling_price,
            $product->stock,
            $product->created_at->format('d/m/Y H:i'),
        ];
    }
}
