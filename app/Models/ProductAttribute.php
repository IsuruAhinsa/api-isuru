<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_id',
        'value_id',
    ];

    /**
     * @param array $input
     * @param Product $product
     * @return void
     */
    final public function storeProductAttribute(array $input, Product $product): void
    {
        $data = $this->prepareAttributeData($input, $product);

        foreach ($data as $attribute) {
            self::query()->create($attribute);
        }
    }

    /**
     * @param array $input
     * @param Product $product
     * @return array
     */
    final public function prepareAttributeData(array $input, Product $product): array
    {
        $attribute_data = [];

        foreach ($input as $value) {
            $data['product_id'] = $product->id;
            $data['attribute_id'] = $value['attribute'];
            $data['value_id'] = $value['value'];
            $attribute_data[] = $data;
        }

        return $attribute_data;
    }

    /**
     * @return BelongsTo
     */
    public function attributes(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    /**
     * @return BelongsTo
     */
    public function attribute_value(): BelongsTo
    {
        return $this->belongsTo(Value::class, 'value_id');
    }
}
