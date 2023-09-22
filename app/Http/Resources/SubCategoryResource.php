<?php

namespace App\Http\Resources;

use App\Helper\ImageManager;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
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
            'photo' => ImageManager::prepareImageUrl(SubCategory::THUMB_IMAGE_UPLOAD_PATH, $this->photo),
            'photo_full' => ImageManager::prepareImageUrl(SubCategory::IMAGE_UPLOAD_PATH, $this->photo),
            'status' => $this->status === 1 ? 'Active' : 'InActive',
            'created_by' => $this->user?->name,
            'category' => $this->category?->name,
            'created_at' => $this->created_at->toDayDateTimeString(),
            'updated_at' => $this->created_at != $this->updated_at ? $this->updated_at->toDayDateTimeString() : 'Not updated yet.',
        ];
    }
}
