<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToModel, WithBatchInserts, WithChunkReading, WithStartRow, WithValidation
{
    private array $mapping;

    private array $categories;

    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
        $this->categories = Category::pluck('id', 'slug')->toArray();
    }

    public function startRow(): int
    {
        return 2; // Assuming row 1 is always headers
    }

    /**
     * @return Model|null
     */
    public function model(array $row)
    {
        $nameIndex = $this->mapping['name'] ?? null;
        $descIndex = $this->mapping['description'] ?? null;
        $catIndex = $this->mapping['category'] ?? null;
        $purchaseIndex = $this->mapping['purchase_price'] ?? null;
        $sellingIndex = $this->mapping['selling_price'] ?? null;
        $stockIndex = $this->mapping['stock'] ?? null;

        // Skip if main identifier is completely missing in row
        if ($nameIndex === null || empty($row[$nameIndex])) {
            return null;
        }

        $slug = isset($row[$catIndex]) ? Str::slug($row[$catIndex]) : null;
        $categoryId = $slug ? ($this->categories[$slug] ?? null) : null;

        return new Product([
            'name' => $row[$nameIndex],
            'description' => $descIndex !== null ? ($row[$descIndex] ?? null) : null,
            'purchase_price' => $purchaseIndex !== null ? ($row[$purchaseIndex] ?? 0) : 0,
            'selling_price' => $sellingIndex !== null ? ($row[$sellingIndex] ?? 0) : 0,
            'stock' => $stockIndex !== null ? ($row[$stockIndex] ?? 0) : 0,
            'category_id' => $categoryId,
        ]);
    }

    public function rules(): array
    {
        $nameIndex = $this->mapping['name'] ?? null;
        $purchaseIndex = $this->mapping['purchase_price'] ?? null;
        $sellingIndex = $this->mapping['selling_price'] ?? null;
        $stockIndex = $this->mapping['stock'] ?? null;

        $rules = [];
        if ($nameIndex !== null) {
            $rules[$nameIndex] = 'required|string|max:255';
        }
        if ($purchaseIndex !== null) {
            $rules[$purchaseIndex] = 'required|numeric|min:0';
        }
        if ($sellingIndex !== null) {
            $rules[$sellingIndex] = 'required|numeric|min:0';
        }
        if ($stockIndex !== null) {
            $rules[$stockIndex] = 'required|integer|min:0';
        }

        return $rules;
    }

    public function customValidationAttributes(): array
    {
        return [
            $this->mapping['name'] ?? '0' => 'Columna Nombre',
            $this->mapping['purchase_price'] ?? '1' => 'Columna P. Compra',
            $this->mapping['selling_price'] ?? '2' => 'Columna P. Venta',
            $this->mapping['stock'] ?? '3' => 'Columna Stock',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
