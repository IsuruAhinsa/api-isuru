<?php

namespace App\Http\Resources;

use App\Helper\ImageManager;
use App\Models\SalesManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesManagerResource extends JsonResource
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
            'created_by' => $this->user?->name,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'nic' => $this->nic,
            'bio' => $this->bio,
            'status' => $this->status === 1 ? 'Active' : 'InActive',
            'photo' => ImageManager::prepareImageUrl(SalesManager::THUMB_IMAGE_UPLOAD_PATH, $this->photo),
            'nic_photo' => ImageManager::prepareImageUrl(SalesManager::NIC_THUMB_IMAGE_UPLOAD_PATH, $this->nic_photo),
            'created_at' => $this->created_at->toDayDateTimeString(),
            'updated_at' => $this->created_at != $this->updated_at ? $this->updated_at->toDayDateTimeString() : 'Not updated yet.',
            'address' => new AddressResource($this->address),
            'shop' => new ShopResource($this->shop),
        ];
    }
}
