<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @return Model|null
     */
    public function model(array $row)
    {
        return new Product([
            'name' => $row['nombre_producto'],
            'description' => $row['descripcion'],
            'purchase_price' => $row['precio_compra'],
            'selling_price' => $row['precio_venta'],
            'stock' => $row['cantidad'],
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre_producto' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0',
        ];
    }
}
