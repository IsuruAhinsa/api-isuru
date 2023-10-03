<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreValueRequest;
use App\Models\ProductAttribute;
use App\Models\Value;
use Illuminate\Http\JsonResponse;

class ProductAttributeValueController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreValueRequest $request, ProductAttribute $productAttribute)
    {
        $request->merge(['user_id' => auth()->id()]);

        $values = new Value($request->all());

        $productAttribute->values()->save($values);

        return response()->json(['msg' => 'Product Attribute Value Added!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductAttribute $productAttribute, Value $value)
    {
        if ($value->product_attribute_id != $productAttribute->id) {
            abort(404);
        }

        $value->delete();

        return response()->json(['msg' => 'Product Attribute Value Deleted!']);
    }

    /**
     * @param ProductAttribute $productAttribute
     * @return JsonResponse
     */
    final public function getProductAttributeValuesList(ProductAttribute $productAttribute): JsonResponse
    {
        $values = (new Value())->getProductAttributeIdAndName($productAttribute);
        return response()->json($values);
    }
}
