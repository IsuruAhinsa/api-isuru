<?php

namespace App\Http\Resources;

use App\Helper\ImageManager;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EditBrandResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->description,
            'logo_preview' => ImageManager::prepareImageUrl(Brand::THUMB_LOGO_UPLOAD_PATH, $this->logo),
            'status' => $this->status,
        ];
    }
}
