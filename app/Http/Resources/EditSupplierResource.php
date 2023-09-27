<?php

namespace App\Http\Resources;

use App\Helper\ImageManager;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EditSupplierResource extends JsonResource
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
            'company' => $this->company,
            'email' => $this->email,
            'phone' => $this->phone,
            'description' => $this->description,
            'logo_preview' => ImageManager::prepareImageUrl(Supplier::THUMB_LOGO_UPLOAD_PATH, $this->logo),
            'created_by' => $this->user?->name,
            'created_at' => $this->created_at->toDayDateTimeString(),
            'updated_at' => $this->created_at != $this->updated_at ? $this->updated_at->toDayDateTimeString() : 'Not updated yet.',
            'status' => $this->status,
            'address' => $this->address?->address,
            'province' => $this->address?->province_id,
            'district' => $this->address?->district_id,
            'city' => $this->address?->city_id,
            'landmark' => $this->address?->landmark,
        ];
    }
}
