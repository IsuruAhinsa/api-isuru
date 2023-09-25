<?php

namespace App\Http\Resources;

use App\Helper\ImageManager;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'logo' => ImageManager::prepareImageUrl(Supplier::THUMB_LOGO_UPLOAD_PATH, $this->logo),
            'created_by' => $this->user?->name,
            'created_at' => $this->created_at->toDayDateTimeString(),
            'updated_at' => $this->created_at != $this->updated_at ? $this->updated_at->toDayDateTimeString() : 'Not updated yet.',
            'province' => $this->province?->name_en,
            'province_si' => $this->province?->name_si,
            'district' => $this->district?->name_en,
            'district_si' => $this->district?->name_si,
            'city' => $this->city?->name_en,
            'city_si' => $this->city?->name_si,
            'landmark' => $this->landmark ?? '-',
            'status' => $this->status === 1 ? 'Active' : 'InActive',
            'address' => $this->address,
        ];
    }
}
