<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Value extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_attribute_id', 'name'];

    /**
     * @return BelongsTo
     */
    public function productAttribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    /**
     * @param $productAttribute
     * @return Collection|array
     */
    final public function getProductAttributeIdAndName($productAttribute): Collection|array
    {
        return self::query()
            ->where('product_attribute_id', $productAttribute->id)
            ->select('id', 'name')
            ->get();
    }
}
