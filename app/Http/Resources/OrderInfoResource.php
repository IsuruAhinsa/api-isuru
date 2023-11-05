<?php

namespace App\Http\Resources;

use App\Helper\ImageManager;
use App\Helper\PriceManager;
use App\Models\ProductPhoto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderInfoResource extends JsonResource
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
            'photo' => ImageManager::prepareImageUrl(ProductPhoto::THUMB_PHOTO_UPLOAD_PATH, $this->photo),
            'brand' => $this->brand?->name,
            'category' => $this->category?->name,
            'sub_category' => $this->sub_category?->name,
            'supplier' => $this->supplier?->company . " " . $this->supplier?->phone,
            'cost' => PriceManager::CURRENCY_SYMBOL . number_format($this->cost, 2),
            'price' => PriceManager::CURRENCY_SYMBOL . number_format($this->price, 2),
            'sale_price' => PriceManager::calculate_sell_price($this->price, $this->discount, $this->discount_fixed, $this->discount_start, $this->discount_end),
            'quantity' => $this->quantity,
            'sku' => $this->sku,
            'discount' => $this->discount . '%',
            'discount_fixed' =>PriceManager::CURRENCY_SYMBOL . $this->discount_fixed,
            'discount_start' => $this->discount_start ? Carbon::create($this->discount_start)->toDayDateTimeString() : null,
            'discount_end' => $this->discount_end ? Carbon::create($this->discount_end)->toDayDateTimeString() : null,
        ];
    }
}
