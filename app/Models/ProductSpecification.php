<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'value',
    ];

    /**
     * @param array $input
     * @param Product $product
     * @return void
     */
    final public function storeProductSpecification(array $input, Product $product): void
    {
        $data = $this->prepareSpecificationData($input, $product);

        foreach ($data as $specification) {
            self::query()->create($specification);
        }
    }

    /**
     * @param array $input
     * @param Product $product
     * @return array
     */
    final public function prepareSpecificationData(array $input, Product $product): array
    {
        $specification_data = [];

        foreach ($input as $value) {
            $data['product_id'] = $product->id;
            $data['name'] = $value['specification_name'];
            $data['value'] = $value['specification_value'];
            $specification_data[] = $data;
        }

        return $specification_data;
    }
}
