<?php

namespace App\Http\Controllers;

use App\Helper\ImageManager;
use App\Http\Resources\EditShopResource;
use App\Http\Resources\ShopResource;
use App\Models\Address;
use App\Models\Shop;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $shops = (new Shop())->getAllShops($request->all());

        return ShopResource::collection($shops);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request)
    {
        $shop = (new Shop())->prepareData($request->all(), auth());
        $address = (new Address())->prepareData($request->all());

        if ($request->has('logo')) {
            $shop['logo'] = ImageManager::imageUploadProcess(
                $request->input('logo'),
                date('Y_m_d_H_i_s'),
                Shop::LOGO_UPLOAD_PATH,
                Shop::THUMB_LOGO_UPLOAD_PATH
            );
        }

        try {
            DB::beginTransaction();
            $shop = Shop::create($shop);
            $shop->address()->create($address);
            DB::commit();
            return response()->json(['msg' => 'Shop Updated Successfully!']);
        } catch (\Throwable $throwable) {
            if ($request->has('logo')) {
                ImageManager::deleteImageWhenExist(
                    $shop->logo,
                    Shop::LOGO_UPLOAD_PATH,
                    Shop::THUMB_LOGO_UPLOAD_PATH
                );
            }

            info('SHOP_STORE_FAILED', ['shop' => $shop, 'address' => $address, 'exception' => $throwable]);

            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        $shop->load('address');

        return new EditShopResource($shop);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShopRequest $request, Shop $shop)
    {
        $shop_record = (new Shop())->prepareData($request->all(), auth());
        $address_record = (new Address())->prepareData($request->all());

        if ($request->has('logo')) {

            ImageManager::deleteImageWhenExist(
                $shop->logo,
                Shop::LOGO_UPLOAD_PATH,
                Shop::THUMB_LOGO_UPLOAD_PATH
            );

            $record['logo'] = ImageManager::imageUploadProcess(
                $request->input('logo'),
                date('Y_m_d_H_i_s'),
                Shop::LOGO_UPLOAD_PATH,
                Shop::THUMB_LOGO_UPLOAD_PATH
            );
        }

        try {
            DB::beginTransaction();
            $shop_record = $shop->update($shop_record);
            $shop->address()->update($address_record);
            DB::commit();
            return response()->json(['msg' => 'Shop Updated Successfully!']);
        } catch (\Throwable $throwable) {
            info('SHOP_UPDATE_FAILED', ['shop' => $shop_record, 'address' => $address_record, 'exception' => $throwable]);
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        ImageManager::deleteImageWhenExist(
            $shop->logo,
            Shop::LOGO_UPLOAD_PATH,
            Shop::THUMB_LOGO_UPLOAD_PATH
        );

        $shop->address()->delete();

        $shop->delete();

        return response()->json(['msg' => 'Shop Deleted Successfully!']);
    }

    /**
     * @return JsonResponse
     */
    final public function getShopsList(): JsonResponse
    {
        $shops = (new Shop())->getShopsSelectList();
        return response()->json($shops);
    }
}
