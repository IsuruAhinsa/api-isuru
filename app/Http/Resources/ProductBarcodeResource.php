<?php

namespace App\Http\Resources;

use App\Helper\PriceManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductBarcodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => PriceManager::CURRENCY_SYMBOL . number_format($this->price, 2),
            'sale_price' => PriceManager::calculate_sell_price($this->price, $this->discount, $this->discount_fixed, $this->discount_start, $this->discount_end),
            'sku' => $this->sku,
        ];
    }
}
