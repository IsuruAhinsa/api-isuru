<?php

namespace App\Http\Resources;

use App\Helper\ImageManager;
use App\Models\ProductPhoto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPhotosResource extends JsonResource
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
            'photo' => ImageManager::prepareImageUrl(ProductPhoto::THUMB_PHOTO_UPLOAD_PATH, $this->photo),
            'original_photo' => ImageManager::prepareImageUrl(ProductPhoto::PHOTO_UPLOAD_PATH, $this->photo),
        ];
    }
}
