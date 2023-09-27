<?php

namespace App\Http\Controllers;

use App\Helper\ImageManager;
use App\Http\Resources\EditSupplierResource;
use App\Http\Resources\SupplierResource;
use App\Models\Address;
use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $supplier = (new Supplier())->prepareData($request->all(), auth());
        $address = (new Address())->prepareData($request->all());

        if ($request->has('logo')) {
            $supplier['logo'] = ImageManager::imageUploadProcess(
                $request->input('logo'),
                date('Y_m_d_H_i_s'),
                Supplier::LOGO_UPLOAD_PATH,
                Supplier::THUMB_LOGO_UPLOAD_PATH
            );
        }

        try {
            DB::beginTransaction();
            $supplier = Supplier::create($supplier);
            $supplier->address()->create($address);
            DB::commit();
            return response()->json(['msg' => 'Supplier Updated Successfully!']);
        } catch (\Throwable $throwable) {
            if ($request->has('logo')) {
                ImageManager::deleteImageWhenExist(
                    $supplier->logo,
                    Supplier::LOGO_UPLOAD_PATH,
                    Supplier::THUMB_LOGO_UPLOAD_PATH
                );
            }

            info('SUPPLIER_STORE_FAILED', ['supplier' => $supplier, 'address' => $address, 'exception' => $throwable]);

            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('address');

        return new EditSupplierResource($supplier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier_record = (new Supplier())->prepareData($request->all(), auth());
        $address_record = (new Address())->prepareData($request->all());

        if ($request->has('logo')) {

            ImageManager::deleteImageWhenExist(
                $supplier->logo,
                Supplier::LOGO_UPLOAD_PATH,
                Supplier::THUMB_LOGO_UPLOAD_PATH
            );

            $record['logo'] = ImageManager::imageUploadProcess(
                $request->input('logo'),
                date('Y_m_d_H_i_s'),
                Supplier::LOGO_UPLOAD_PATH,
                Supplier::THUMB_LOGO_UPLOAD_PATH
            );
        }

        try {
            DB::beginTransaction();
            $supplier_record = $supplier->update($supplier_record);
            $supplier->address()->update($address_record);
            DB::commit();
            return response()->json(['msg' => 'Supplier Updated Successfully!']);
        } catch (\Throwable $throwable) {
            info('SUPPLIER_UPDATE_FAILED', ['supplier' => $supplier_record, 'address' => $address_record, 'exception' => $throwable]);
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        ImageManager::deleteImageWhenExist(
            $supplier->logo,
            Supplier::LOGO_UPLOAD_PATH,
            Supplier::THUMB_LOGO_UPLOAD_PATH
        );

        $supplier->address()->delete();

        $supplier->delete();

        return response()->json(['msg' => 'Supplier Deleted Successfully!']);
    }
}
