<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductAttributeResource;
use App\Models\ProductAttribute;
use App\Http\Requests\StoreProductAttributeRequest;
use App\Http\Requests\UpdateProductAttributeRequest;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $product_attributes = (new ProductAttribute())->getProductAttributes($request->all());
        return ProductAttributeResource::collection($product_attributes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductAttributeRequest $request)
    {
        $request->merge(['user_id' => auth()->id()]);

        ProductAttribute::create($request->all());

        return response()->json(['msg' => 'Product Attribute Added!']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductAttributeRequest $request, ProductAttribute $productAttribute)
    {
        $productAttribute->update($request->all());

        return response()->json(['msg' => 'Product Attribute Updated!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductAttribute $productAttribute)
    {
        $productAttribute->delete();
        return response()->json(['msg' => 'Product Attribute Deleted!']);
    }
}
