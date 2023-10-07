<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPhoto extends Model
{
    use HasFactory;

    public const PHOTO_UPLOAD_PATH = 'images/uploads/product/';
    public const THUMB_PHOTO_UPLOAD_PATH = 'images/uploads/product_thumb/';

    protected $fillable = [
        'product_id', 'photo', 'is_primary'
    ];

    /**
     * @param array $input
     * @return Model|Builder
     */
    final public function storeProductPhoto(array $input): Model|Builder
    {
        return self::query()->create($input);
    }
}
