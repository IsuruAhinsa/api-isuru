<?php

namespace App\Http\Resources;

use App\Helper\ImageManager;
use App\Models\SalesManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EditSalesManagerResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'nic' => $this->nic,
            'status' => $this->status,
            'address' => $this->address?->address,
            'shop' => $this->shop?->id,
            'province' => $this->address?->province_id,
            'district' => $this->address?->district_id,
            'city' => $this->address?->city_id,
            'landmark' => $this->address?->landmark,
            'bio' => $this->bio,
            'photo_preview' => ImageManager::prepareImageUrl(SalesManager::THUMB_IMAGE_UPLOAD_PATH, $this->photo),
            'nic_photo_preview' => ImageManager::prepareImageUrl(SalesManager::NIC_THUMB_IMAGE_UPLOAD_PATH, $this->nic_photo),
        ];
    }
}
