<?php

namespace App\Http\Controllers;

use App\Helper\ImageManager;
use App\Http\Requests\StoreProductPhotoRequest;
use App\Http\Requests\UpdateProductPhotoRequest;
use App\Models\Product;
use App\Models\ProductPhoto;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

class ProductPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @throws Exception
     */
    public function store(StoreProductPhotoRequest $request, Product $product)
    {
        if ($request->has('photos')) {
            foreach ($request->photos as $photo) {
                $name = Str::slug($product->slug . '-' . Carbon::now()->toDayDateTimeString() . '-' . random_int(10000, 99999));
                $data['product_id'] = $product->id;
                $data['is_primary'] = $photo['is_primary'];

                $data['photo'] = ImageManager::imageUploadProcess(
                    $photo['photo'],
                    $name,
                    ProductPhoto::PHOTO_UPLOAD_PATH,
                    ProductPhoto::THUMB_PHOTO_UPLOAD_PATH
                );

                (new ProductPhoto())->storeProductPhoto($data);
            }
        }

        return response()->json(['msg' => 'Product Photos Added Successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product, ProductPhoto $productPhoto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductPhotoRequest $request, Product $product, ProductPhoto $productPhoto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductPhoto $productPhoto)
    {
        //
    }
}
