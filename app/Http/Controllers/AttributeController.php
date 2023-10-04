<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttributeResource;
use App\Models\Attribute;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $attributes = (new Attribute())->getProductAttributes($request->all());
        return AttributeResource::collection($attributes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttributeRequest $request)
    {
        $request->merge(['user_id' => auth()->id()]);

        Attribute::create($request->all());

        return response()->json(['msg' => 'Attribute Added!']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeRequest $request, Attribute $attribute)
    {
        $attribute->update($request->all());

        return response()->json(['msg' => 'Attribute Updated!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return response()->json(['msg' => 'Attribute Deleted!']);
    }

    /**
     * @return JsonResponse
     */
    final public function getAttributeListWithValues(): JsonResponse
    {
        $attributes = (new Attribute())->getAttributeIdAndNameWithValues();
        return response()->json($attributes);
    }
}
