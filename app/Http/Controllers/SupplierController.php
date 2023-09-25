<?php

namespace App\Http\Controllers;

use App\Helper\ImageManager;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $suppliers = (new Supplier())->getAllSuppliers($request->all());

        return SupplierResource::collection($suppliers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        $supplier = $request->except('logo');

        $supplier['user_id'] =  auth()->id();
        $supplier['province_id'] =  $request->input('province');
        $supplier['district_id'] =  $request->input('district');
        $supplier['city_id'] =  $request->input('city');

        if ($request->has('logo')) {
            $supplier['logo'] = $this->imageUpload($request->input('logo'), date('Y_m_d_H_i_s'));
        }

        (new Supplier())->storeSupplier($supplier);

        return response()->json(['msg' => 'Supplier Added Successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return new SupplierResource($supplier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $record = $request->except('logo');

        $record['province_id'] =  $request->input('province');
        $record['district_id'] =  $request->input('district');
        $record['city_id'] =  $request->input('city');

        if ($request->has('logo')) {
            $this->deleteImageWhenExist($supplier->logo);
            $record['logo'] = $this->imageUpload($request->input('logo'), date('Y_m_d_H_i_s'));
        }

        $supplier->update($record);

        return response()->json(['msg' => 'Supplier Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $this->deleteImageWhenExist($supplier->logo);

        $supplier->delete();

        return response()->json(['msg' => 'Supplier Deleted Successfully!']);
    }

    private function imageUpload($file, $slug)
    {
        $photo_name = ImageManager::uploadImage($slug, 800, 800, Supplier::LOGO_UPLOAD_PATH, $file);

        ImageManager::uploadImage($slug, 150, 150, Supplier::THUMB_LOGO_UPLOAD_PATH, $file);

        return $photo_name;
    }

    private function deleteImageWhenExist($logo)
    {
        if (!empty($logo)) {
            ImageManager::deleteImage(Supplier::LOGO_UPLOAD_PATH, $logo);
            ImageManager::deleteImage(Supplier::THUMB_LOGO_UPLOAD_PATH, $logo);
        }
    }
}
