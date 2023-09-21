<?php

namespace App\Http\Controllers;

use App\Helper\ImageManager;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\EditCategoryResource;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = (new Category())->getAllCategories($request->all());

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = $request->except('photo');

        $category['slug'] = $slug = Str::slug($request->input('slug'));

        $category['user_id'] = auth()->id();

        if ($request->has('photo')) {
            $category['photo'] = $this->imageUpload($request->input('photo'), $slug);
        }

        (new Category())->storeCategory($category);

        return response()->json(['msg' => 'Category Created Successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new EditCategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $record = $request->except('photo');

        $record['slug'] = $slug = Str::slug($request->input('slug'));

        if ($request->has('photo')) {
            $this->deleteImageWhenExist($category->photo);
            $record['photo'] = $this->imageUpload($request->input('photo'), $slug);
        }

        $category->update($record);

        return response()->json(['msg' => 'Category Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->deleteImageWhenExist($category->photo);

        $category->delete();

        return response()->json(['msg' => 'Category deleted successfully!']);
    }

    private function imageUpload($file, $slug)
    {
        $photo_name = ImageManager::uploadImage($slug, 800, 800, Category::IMAGE_UPLOAD_PATH, $file);

        ImageManager::uploadImage($slug, 150, 150, Category::THUMB_IMAGE_UPLOAD_PATH, $file);

        return $photo_name;
    }

    private function deleteImageWhenExist($photo)
    {
        if (!empty($photo)) {
            ImageManager::deleteImage(Category::IMAGE_UPLOAD_PATH, $photo);
            ImageManager::deleteImage(Category::THUMB_IMAGE_UPLOAD_PATH, $photo);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getCategoriesList(): JsonResponse
    {
        $categories = (new Category())->getCategoryIdAndName();
        return response()->json($categories);
    }
}
