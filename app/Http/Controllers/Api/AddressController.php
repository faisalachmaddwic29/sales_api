<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class AddressController extends Controller
{

    public function provinces()
    {
        try {
            return response_api_success(DB::table('provinces')->orderBy('province_name', 'asc')->get());
        } catch (\Exception $e) {
            return  response_api_server_error($e->getMessage());
        }
    }

    public function regencies(Request $request)
    {
        try {
            return response_api_success(DB::table('regencies')->where('id_province', $request->id_province)->orderBy('regency_name', 'asc')->get());
        } catch (\Exception $e) {
            return response_api_server_error($e->getMessage());
        }
    }

    public function districts(Request $request)
    {
        try {
            return response_api_success(DB::table('districts')->where('id_regency', $request->id_regency)->orderBy('district_name', 'asc')->get());
        } catch (\Exception $e) {
            return response_api_server_error($e->getMessage());
        }
    }

    public function villages(Request $request)
    {
        try {
            return response_api_success(DB::table('villages')->where('id_district', $request->id_district)->orderBy('village_name', 'asc')->get());
        } catch (\Exception $e) {
            return response_api_server_error($e->getMessage());
        }
    }
}
