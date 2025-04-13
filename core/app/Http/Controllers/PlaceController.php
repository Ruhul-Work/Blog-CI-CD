<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Division;
use App\Models\Union;
use App\Models\Upazila;
use Illuminate\Http\Request;

class PlaceController extends Controller
{

    public function getCitiesByCountry(Request $request)
    {

        $country_id = (int) $request->country_id;

        $data = City::where('country_id', $country_id)->orderBy('name','ASC')->get();

        return $data;
    }

    public function getDivisions()
    {
        return response()->json(Division::orderBy('name', 'asc')->get());
    }

    public function getDistrictsBYDivisions(Request $request)

    {
        $divisionId=$request->division_id;

        return response()->json(City::where('division_id', $divisionId)->orderBy('name','ASC')->get());
    }


    public function getUpazilasByCity(Request $request)
    {

        $city_id = (int) $request->city_id;
        $data = Upazila::where('district_id', $city_id)->orderBy('name','ASC')->get();

        return $data;
    }
    public function getUnionsByUpazila(Request $request)
    {

        $upazila_id = (int) $request->upazila_id;

        $data = Union::where('upazila_id', $upazila_id)->orderBy('name','ASC')->get();

        return $data;
    }
}
