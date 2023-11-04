<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * @param array $order_details_data
     * @param $order
     * @return void
     */
    public function storeOrderDetails(array $order_details_data, $order)
    {
        foreach ($order_details_data as $product) {
            $order_details = $this->prepareData($product, $order);
            self::query()->create($order_details);
        }
    }

    /**
     * @param $product
     * @param $order
     * @return array
     */
    public function prepareData($product, $order): array
    {
        return [
            'order_id' => $order->id,
            'brand_id' => $product->brand_id,
            'category_id' => $product->category_id,
            'sub_category_id' => $product->sub_category_id,
            'supplier_id' => $product->supplier_id,
            'name' => $product->name,
            'cost' => $order->id,
            'discount_start' => $product->discount_start,
            'discount_end' => $product->discount_end,
            'discount' => $product->discount,
            'discount_fixed' => $product->discount_fixed,
            'price' => $product->price,
            'sku' => $product->sku,
            'quantity' => $product->quantity,
            'photo' => $product->primary_photo?->photo,
        ];
    }
}
