<?php

namespace App\Http\Controllers;

use App\Helper\ImageManager;
use App\Http\Resources\BrandResource;
use App\Http\Resources\EditBrandResource;
use App\Models\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brands = (new Brand())->getAllBrands($request->all());

        return BrandResource::collection($brands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $brand = $request->except('logo');

        $brand['slug'] = $slug = Str::slug($request->input('slug'));

        $brand['user_id'] = auth()->id();

        if ($request->has('logo')) {
            $brand['logo'] = $this->imageUpload($request->input('logo'), $slug);
        }

        (new Brand())->storeBrand($brand);

        return response()->json(['msg' => 'Brand Created Successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return new EditBrandResource($brand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $record = $request->except('logo');

        $record['slug'] = $slug = Str::slug($request->input('slug'));

        if ($request->has('logo')) {
            $this->deleteImageWhenExist($brand->logo);
            $record['logo'] = $this->imageUpload($request->input('logo'), $slug);
        }

        $brand->update($record);

        return response()->json(['msg' => 'Brand Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $this->deleteImageWhenExist($brand->logo);

        $brand->delete();

        return response()->json(['msg' => 'Brand deleted successfully!']);
    }

    private function imageUpload($file, $slug)
    {
        $logo_name = ImageManager::uploadImage($slug, 800, 800, Brand::LOGO_UPLOAD_PATH, $file);

        ImageManager::uploadImage($slug, 150, 150, Brand::THUMB_LOGO_UPLOAD_PATH, $file);

        return $logo_name;
    }

    private function deleteImageWhenExist($logo)
    {
        if (!empty($logo)) {
            ImageManager::deleteImage(Brand::LOGO_UPLOAD_PATH, $logo);
            ImageManager::deleteImage(Brand::THUMB_LOGO_UPLOAD_PATH, $logo);
        }
    }
}
