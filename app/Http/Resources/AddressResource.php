<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'address' => $this->address,
            'province' => $this->province?->name_en,
            'province_si' => $this->province?->name_si,
            'district' => $this->district?->name_en,
            'district_si' => $this->district?->name_si,
            'city' => $this->city?->name_en,
            'city_si' => $this->city?->name_si,
            'landmark' => $this->landmark ?? '-',
        ];
    }
}
