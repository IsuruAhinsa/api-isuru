<?php

namespace App\Http\Controllers;

use App\Helper\ImageManager;
use App\Http\Resources\EditSubCategoryResource;
use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = (new SubCategory())->getAllSubCategories($request->all());

        return SubCategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubCategoryRequest $request)
    {
        $sub_category = $request->except('photo');

        $sub_category['slug'] = $slug = Str::slug($request->input('slug'));

        $sub_category['user_id'] = auth()->id();

        $sub_category['category_id'] = $request->input('category');

        if ($request->has('photo')) {
            $sub_category['photo'] = $this->imageUpload($request->input('photo'), $slug);
        }

        (new SubCategory())->storeSubCategory($sub_category);

        return response()->json(['msg' => 'Sub Category Created Successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(SubCategory $subCategory)
    {
        return new EditSubCategoryResource($subCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubCategoryRequest $request, SubCategory $subCategory)
    {
        $record = $request->except('photo');

        $record['slug'] = $slug = Str::slug($request->input('slug'));

        if ($request->has('photo')) {
            $this->deleteImageWhenExist($subCategory->photo);
            $record['photo'] = $this->imageUpload($request->input('photo'), $slug);
        }

        $subCategory->update($record);

        return response()->json(['msg' => 'Sub Category Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        $this->deleteImageWhenExist($subCategory->photo);

        $subCategory->delete();

        return response()->json(['msg' => 'Category deleted successfully!']);
    }

    private function imageUpload($file, $slug)
    {
        $photo_name = ImageManager::uploadImage($slug, 800, 800, SubCategory::IMAGE_UPLOAD_PATH, $file);

        ImageManager::uploadImage($slug, 150, 150, SubCategory::THUMB_IMAGE_UPLOAD_PATH, $file);

        return $photo_name;
    }

    private function deleteImageWhenExist($photo)
    {
        if (!empty($photo)) {
            ImageManager::deleteImage(SubCategory::IMAGE_UPLOAD_PATH, $photo);
            ImageManager::deleteImage(SubCategory::THUMB_IMAGE_UPLOAD_PATH, $photo);
        }
    }
}
