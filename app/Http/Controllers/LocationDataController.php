<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LocationDataController extends Controller
{
    final public function getProvinces(): JsonResponse
    {
        $provinces = DB::table('provinces')
            ->select(['id', 'name_en', 'name_si'])
            ->get();

        return response()->json($provinces);
    }

    final public function getDistrictsByProvinceId(int $province_id)
    {
        $districts = DB::table('districts')
            ->select(['id', 'name_en', 'name_si'])
            ->where('province_id', $province_id)
            ->get();

        return response()->json($districts);
    }

    final public function getCitiesByDistrictId(int $district_id)
    {
        $cities = DB::table('cities')
            ->select(['id', 'name_en', 'name_si'])
            ->where('district_id', $district_id)
            ->get();

        return response()->json($cities);
    }
}
