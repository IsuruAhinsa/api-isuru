<?php

namespace App\Http\Controllers;

use App\Helper\ImageManager;
use App\Http\Resources\BrandResource;
use App\Http\Resources\EditBrandResource;
use App\Models\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use Illuminate\Http\JsonResponse;
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
            $brand['logo'] = ImageManager::imageUploadProcess(
                $request->input('logo'),
                $slug,
                Brand::LOGO_UPLOAD_PATH,
                Brand::THUMB_LOGO_UPLOAD_PATH
            );;
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
            ImageManager::deleteImageWhenExist(
                $brand->logo,
                Brand::LOGO_UPLOAD_PATH,
                Brand::THUMB_LOGO_UPLOAD_PATH
            );

            $record['logo'] = ImageManager::imageUploadProcess(
                $request->input('logo'),
                $slug,
                Brand::LOGO_UPLOAD_PATH,
                Brand::THUMB_LOGO_UPLOAD_PATH
            );
        }

        $brand->update($record);

        return response()->json(['msg' => 'Brand Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        ImageManager::deleteImageWhenExist(
            $brand->logo,
            Brand::LOGO_UPLOAD_PATH,
            Brand::THUMB_LOGO_UPLOAD_PATH
        );

        $brand->delete();

        return response()->json(['msg' => 'Brand deleted successfully!']);
    }

    /**
     * @return JsonResponse
     */
    final public function getBrandsList(): JsonResponse
    {
        $brands = (new Brand())->getBrandIdAndName();
        return response()->json($brands);
    }
}
