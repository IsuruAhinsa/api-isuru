<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreValueRequest;
use App\Models\Attribute;
use App\Models\Value;

class ProductAttributeValueController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreValueRequest $request, Attribute $attribute)
    {
        $request->merge(['user_id' => auth()->id()]);

        $values = new Value($request->all());

        $attribute->values()->save($values);

        return response()->json(['msg' => 'Product Attribute Value Added!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute, Value $value)
    {
        if ($value->attribute_id != $attribute->id) {
            abort(404);
        }

        $value->delete();

        return response()->json(['msg' => 'Product Attribute Value Deleted!']);
    }
}
