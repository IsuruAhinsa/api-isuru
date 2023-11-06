<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductBarcodeResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ProductAttribute;
use App\Models\ProductSpecification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = (new Product())->getAllProducts($request->all());

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $product = (new Product())->storeProduct($request->except('photo', 'attributes', 'specification'));

            if ($request->has('attributes')) {
                (new ProductAttribute())->storeProductAttribute($request->input('attributes'), $product);
            }

            if ($request->has('specifications')) {
                (new ProductSpecification())->storeProductSpecification($request->input('specifications'), $product);
            }

            DB::commit();

            return response()->json(['msg' => 'Product Saved Successfully!', 'product_id' => $product->id]);

        } catch (\Throwable $throwable) {
            info('PRODUCT_SAVE_FAILED', ['data' => $request->all(), 'error' => $throwable->getMessage()]);

            DB::rollBack();

            return response()->json(['msg' => $throwable->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }

    public function getProductListForBarcode(Request $request)
    {
        $products = (new Product())->getProductsForBarcode($request->all());
        return ProductBarcodeResource::collection($products);
    }
}
