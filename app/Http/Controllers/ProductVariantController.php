<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $product->load(['attributes.values', 'variants.attributeValues']);

        return view('products.variants.index', compact('product'));
    }

    public function storeAttribute(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $product->attributes()->create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Atributo creado.');
    }

    public function storeValue(Request $request, ProductAttribute $attribute)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'hex_color' => 'nullable|string|max:7',
        ]);

        $attribute->values()->create($request->only('value', 'hex_color'));

        return back()->with('success', 'Valor añadido.');
    }

    public function generateVariants(Product $product)
    {
        $attributes = $product->attributes()->with('values')->get();

        if ($attributes->isEmpty()) {
            return back()->with('error', 'Crea al menos un atributo primero.');
        }

        $attributeValues = $attributes->map(fn ($attr) => $attr->values->pluck('id'));

        if ($attributeValues->contains(fn ($vals) => $vals->isEmpty())) {
            return back()->with('error', 'Todos los atributos deben tener al menos un valor.');
        }

        $attributeValues = $attributeValues->toArray();

        // Generate combinations (Cartesian Product)
        $combinations = [[]];
        foreach ($attributeValues as $values) {
            $newCombinations = [];
            foreach ($combinations as $combination) {
                foreach ($values as $value) {
                    $newCombinations[] = array_merge($combination, [$value]);
                }
            }
            $combinations = $newCombinations;
        }

        $createdCount = 0;
        foreach ($combinations as $combination) {
            // Check if variant already exists
            $existingVariant = $product->variants()->whereHas('attributeValues', function ($query) use ($combination) {
                $query->whereIn('attribute_value_id', $combination);
            }, '=', count($combination))->first();

            if (! $existingVariant) {
                $variant = $product->variants()->create([
                    'sku' => strtoupper(Str::random(8)),
                    'stock' => 0,
                ]);
                $variant->attributeValues()->attach($combination);
                $createdCount++;
            }
        }

        return back()->with('success', "Se generaron $createdCount nuevas variantes.");
    }

    public function updateVariant(Request $request, ProductVariant $variant)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
            'price_override' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:255|unique:product_variants,sku,'.$variant->id,
        ]);

        $variant->update($request->only('stock', 'price_override', 'sku'));

        return back()->with('success', 'Variante actualizada.');
    }

    public function destroyVariant(ProductVariant $variant)
    {
        $variant->delete();

        return back()->with('success', 'Variante eliminada.');
    }

    public function getVariantsApi(Product $product)
    {
        $variants = $product->variants()->with('attributeValues')->get()->map(function ($variant) {
            return [
                'id' => $variant->id,
                'label' => $variant->label,
                'stock' => $variant->stock,
                'price' => (float) $variant->effective_price,
            ];
        });

        return response()->json($variants);
    }

    public function destroyAttribute(ProductAttribute $attribute)
    {
        $attribute->delete();

        return back()->with('success', 'Atributo eliminado.');
    }
}
