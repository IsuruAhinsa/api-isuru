<?php

namespace App\Http\Controllers;

use App\Helper\ImageManager;
use App\Http\Resources\EditSalesManagerResource;
use App\Http\Resources\SalesManagerResource;
use App\Models\Address;
use App\Models\SalesManager;
use App\Http\Requests\StoreSalesManagerRequest;
use App\Http\Requests\UpdateSalesManagerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sales_managers = (new SalesManager())->getAllSalesManagers($request->all());
        return SalesManagerResource::collection($sales_managers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalesManagerRequest $request)
    {
        $sales_manager = (new SalesManager())->prepareData($request->except('photo', 'nic_photo'));
        $address = (new Address())->prepareData($request->only('address', 'province', 'district', 'city', 'landmark'));

        if ($request->has('photo')) {
            $sales_manager['photo'] = ImageManager::imageUploadProcess(
                $request->input('photo'),
                date('Y_m_d_H_i_s'),
                SalesManager::IMAGE_UPLOAD_PATH,
                SalesManager::THUMB_IMAGE_UPLOAD_PATH
            );
        }

        if ($request->has('nic_photo')) {
            $sales_manager['nic_photo'] = ImageManager::imageUploadProcess(
                $request->input('nic_photo'),
                $request->input('nic') . '_' . date('Y_m_d_H_i_s'),
                SalesManager::NIC_IMAGE_UPLOAD_PATH,
                SalesManager::NIC_THUMB_IMAGE_UPLOAD_PATH
            );
        }

        try {
            DB::beginTransaction();
            $sales_manager = SalesManager::create($sales_manager);
            $sales_manager->address()->create($address);
            DB::commit();
            return response()->json(['msg' => 'Sales Manager Added Successfully!']);
        } catch (\Throwable $throwable) {
            if ($request->has('photo')) {
                ImageManager::deleteImageWhenExist(
                    $sales_manager->photo,
                    SalesManager::IMAGE_UPLOAD_PATH,
                    SalesManager::THUMB_IMAGE_UPLOAD_PATH
                );
            }

            if ($request->has('nic_photo')) {
                ImageManager::deleteImageWhenExist(
                    $sales_manager->nic_photo,
                    SalesManager::NIC_IMAGE_UPLOAD_PATH,
                    SalesManager::NIC_THUMB_IMAGE_UPLOAD_PATH
                );
            }

            info('SALES_MANAGER_STORE_FAILED', ['sales_manager' => $sales_manager, 'address' => $address, 'exception' => $throwable]);

            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesManager $salesManager)
    {
        $salesManager->load(['address', 'shop']);

        return new EditSalesManagerResource($salesManager);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalesManagerRequest $request, SalesManager $salesManager)
    {
        $supplier_record = (new SalesManager())->prepareData($request->except('photo', 'nic_photo'));
        $address_record = (new Address())->prepareData($request->only('address', 'province', 'district', 'city', 'landmark'));

        if ($request->has('photo')) {

            ImageManager::deleteImageWhenExist(
                $salesManager->photo,
                SalesManager::IMAGE_UPLOAD_PATH,
                SalesManager::THUMB_IMAGE_UPLOAD_PATH
            );

            $supplier_record['photo'] = ImageManager::imageUploadProcess(
                $request->input('photo'),
                date('Y_m_d_H_i_s'),
                SalesManager::IMAGE_UPLOAD_PATH,
                SalesManager::THUMB_IMAGE_UPLOAD_PATH
            );
        }

        if ($request->has('nic_photo')) {

            ImageManager::deleteImageWhenExist(
                $salesManager->nic_photo,
                SalesManager::NIC_IMAGE_UPLOAD_PATH,
                SalesManager::NIC_THUMB_IMAGE_UPLOAD_PATH
            );

            $supplier_record['nic_photo'] = ImageManager::imageUploadProcess(
                $request->input('nic_photo'),
                $request->input('nic') . '_' . date('Y_m_d_H_i_s'),
                SalesManager::NIC_IMAGE_UPLOAD_PATH,
                SalesManager::NIC_THUMB_IMAGE_UPLOAD_PATH
            );
        }

        try {
            DB::beginTransaction();
            $supplier_record = $salesManager->update($supplier_record);
            $salesManager->address()->update($address_record);
            DB::commit();
            return response()->json(['msg' => 'Sales Manager Updated Successfully!']);
        } catch (\Throwable $throwable) {
            info('SALES_MANAGER_UPDATE_FAILED', ['sales_manager' => $supplier_record, 'address' => $address_record, 'exception' => $throwable]);
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesManager $salesManager)
    {
        ImageManager::deleteImageWhenExist(
            $salesManager->photo,
            SalesManager::IMAGE_UPLOAD_PATH,
            SalesManager::THUMB_IMAGE_UPLOAD_PATH
        );

        ImageManager::deleteImageWhenExist(
            $salesManager->nic_photo,
            SalesManager::NIC_IMAGE_UPLOAD_PATH,
            SalesManager::NIC_THUMB_IMAGE_UPLOAD_PATH
        );

        $salesManager->address()->delete();

        $salesManager->delete();

        return response()->json(['msg' => 'Sales Manager Deleted Successfully!']);
    }
}
