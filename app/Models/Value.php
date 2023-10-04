<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Value extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'attribute_id', 'name'];

    /**
     * @return BelongsTo
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * @param $attribute
     * @return Collection|array
     */
    final public function getAttributeIdAndName($attribute): Collection|array
    {
        return self::query()
            ->where('attribute_id', $attribute->id)
            ->select('id', 'name')
            ->get();
    }
}
