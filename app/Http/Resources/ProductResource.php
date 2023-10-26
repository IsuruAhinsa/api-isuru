<?php

namespace App\Http\Resources;

use App\Helper\ImageManager;
use App\Helper\PriceManager;
use App\Models\ProductPhoto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'brand' => $this->brand?->name,
            'country' => $this->country?->name,
            'supplier' => $this->supplier?->company . " " . $this->supplier?->phone,
            'subCategory' => $this->sub_category?->name,
            'createdBy' => $this->created_by?->name,
            'updatedBy' => $this->updated_by?->name,
            'category' => $this->category?->name,
            'name' => $this->name,
            'cost' => PriceManager::CURRENCY_SYMBOL . $this->cost,
            'price' => PriceManager::CURRENCY_SYMBOL . $this->price,
            'original_price' => $this->price,
            'sale_price' => PriceManager::calculate_sell_price($this->price, $this->discount, $this->discount_fixed, $this->discount_start, $this->discount_end),
            'stock' => $this->stock,
            'discount_percentage' => $this->discount . '%',
            'discount_fixed' => PriceManager::CURRENCY_SYMBOL . $this->discount_fixed,
            'discount_end' => Carbon::create($this->discount_end)->toDayDateTimeString(),
            'discount_start' => Carbon::create($this->discount_start)->toDayDateTimeString(),
            'sku' => $this->sku,
            'description' => $this->description,
            'status' => $this->status === 1 ? 'Active' : 'InActive',
            'photo' => ImageManager::prepareImageUrl(ProductPhoto::THUMB_PHOTO_UPLOAD_PATH, $this->primary_photo?->photo),
            'created_at' => $this->created_at->toDayDateTimeString(),
            'updated_at' => $this->created_at != $this->updated_at ? $this->updated_at->toDayDateTimeString() : 'Not updated yet.',
            'attributes' => ProductAttributeResource::collection($this->product_attributes),
        ];
    }
}
