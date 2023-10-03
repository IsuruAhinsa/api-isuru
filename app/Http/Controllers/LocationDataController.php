<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class LocationDataController extends Controller
{
    final public function getProvinces(): JsonResponse
    {
        $provinces = DB::table('provinces')
            ->select('id', 'name_en')
            ->get();

        return response()->json($provinces);
    }

    final public function getDistrictsByProvinceId(int $province_id)
    {
        $districts = DB::table('districts')
            ->select('id', 'name_en')
            ->where('province_id', $province_id)
            ->get();

        return response()->json($districts);
    }

    final public function getCitiesByDistrictId(int $district_id)
    {
        $cities = DB::table('cities')
            ->select('id', 'name_en')
            ->where('district_id', $district_id)
            ->get();

        return response()->json($cities);
    }

    public function storeCountries()
    {
        $url = 'https://restcountries.com/v3.1/all';
        $response = Http::get($url);
        $response = json_decode($response, true);

        foreach ($response as $country) {
            $data['name'] = $country['name']['common'];
            Country::create($data);
        }
    }

    /**
     * @return JsonResponse
     */
    final public function getCountriesList(): JsonResponse
    {
        $countries = (new Country())->getCountryIdAndName();
        return response()->json($countries);
    }
}
