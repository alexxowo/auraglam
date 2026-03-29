<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'sku', 'stock', 'price_override'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(ProductAttributeValue::class, 'product_variant_attribute_values', 'product_variant_id', 'attribute_value_id');
    }

    /**
     * Get the effective price of the variant.
     */
    protected function effectivePrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->price_override ?? $this->product->selling_price,
        );
    }

    /**
     * Get the descriptive label of the variant (e.g. Rojo / M).
     */
    protected function label(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributeValues->pluck('value')->implode(' / '),
        );
    }
}
