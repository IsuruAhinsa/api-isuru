<?php

namespace App\Http\Resources;

use App\Helper\ImageManager;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EditCategoryResource extends JsonResource
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
            'serial' => $this->serial,
            'description' => $this->description,
            'photo_preview' => ImageManager::prepareImageUrl(Category::THUMB_IMAGE_UPLOAD_PATH, $this->photo),
            'status' => $this->status,
        ];
    }
}
