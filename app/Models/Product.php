<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'purchase_price',
        'selling_price',
        'stock',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the product's margin.
     */
    protected function margin(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->selling_price - $this->purchase_price,
        );
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Check if the product has variants.
     */
    protected function hasVariants(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->variants()->exists(),
        );
    }

    /**
     * Get the total stock (sum of variants or base stock).
     */
    protected function totalStock(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->has_variants ? $this->variants()->sum('stock') : $this->stock,
        );
    }

    /**
     * Get the product's image URL.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image_path ? \Illuminate\Support\Facades\Storage::url($this->image_path) : null,
        );
    }

    /**
     * Generate variants for the product based on attributes and values.
     *
     * @param  array  $attributesData  Array of attributes with their values [['name' => 'Color', 'values' => ['Red', 'Blue']]]
     */
    public function generateVariantsFromData(array $attributesData): void
    {
        $attributeIds = [];

        foreach ($attributesData as $data) {
            $attribute = $this->attributes()->create(['name' => $data['name']]);
            foreach ($data['values'] as $value) {
                $attributeValue = $attribute->values()->create(['value' => trim($value)]);
                $attributeIds[$attribute->id][] = $attributeValue->id;
            }
        }

        if (empty($attributeIds)) {
            return;
        }

        // Generate combinations (Cartesian Product)
        $combinations = [[]];
        foreach ($attributeIds as $values) {
            $newCombinations = [];
            foreach ($combinations as $combination) {
                foreach ($values as $value) {
                    $newCombinations[] = array_merge($combination, [$value]);
                }
            }
            $combinations = $newCombinations;
        }

        foreach ($combinations as $combination) {
            $variant = $this->variants()->create([
                'sku' => strtoupper(Str::random(8)),
                'stock' => 0,
            ]);
            $variant->attributeValues()->attach($combination);
        }
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
