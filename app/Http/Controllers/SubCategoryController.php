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
            $sub_category['photo'] = ImageManager::imageUploadProcess(
                $request->input('photo'),
                $slug,
                SubCategory::IMAGE_UPLOAD_PATH,
                SubCategory::THUMB_IMAGE_UPLOAD_PATH
            );
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
            ImageManager::deleteImageWhenExist(
                $subCategory->photo,
                SubCategory::IMAGE_UPLOAD_PATH,
                SubCategory::THUMB_IMAGE_UPLOAD_PATH
            );

            $record['photo'] = ImageManager::imageUploadProcess(
                $request->input('photo'),
                $slug,
                SubCategory::IMAGE_UPLOAD_PATH,
                SubCategory::THUMB_IMAGE_UPLOAD_PATH
            );
        }

        $subCategory->update($record);

        return response()->json(['msg' => 'Sub Category Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        ImageManager::deleteImageWhenExist(
            $subCategory->photo,
            SubCategory::IMAGE_UPLOAD_PATH,
            SubCategory::THUMB_IMAGE_UPLOAD_PATH
        );

        $subCategory->delete();

        return response()->json(['msg' => 'Category deleted successfully!']);
    }
}
