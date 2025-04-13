<?php

namespace App\Http\Controllers\backend\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Country;
use App\Models\City;
use App\Models\Upazila;

class CountryCityController extends Controller
{
    public function index()
    {
        return view('backend.modules.setting.country');
    }

    //
    public function countryList()
    {
        // Fetch all countries from the database
        $countries = Country::all();

        // Initialize an empty array to hold the formatted country data
        $countryList = [];

        // Loop through each country and add it to the array
        foreach ($countries as $country) {
            $countryList[] = [
                'id' => $country->id,
                'name' => $country->name,
            ];
        }

        // Return the formatted country data as a JSON response
        return response()->json([
            'data' => $countryList
        ]);
    }
    //
    public function storeCountry(Request $request)
    {

        $rules = [
            'name' => 'required',

        ];
        $messages = [
            'name' => 'Country name is Required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $review = new Country();
        $review->name = $request->name;
        $review->save();
        return response()->json([
            'message' => 'Saved Successfully',
        ], 201);
    }
    //
    public function singleCountry($id)
    {

        $data = Country::find($id);

        return response()->json([
            'data' => $data
        ]);
    }
    //
    public function countryUpdate(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name' => 'Name is Required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $country = Country::find($request->id);
        $country->name = $request->name;
        $country->save();

        return response()->json([

            'message' => 'Saved Successfully',

        ], 201);
    }

    public function countryDestroy($id)
    {

        $country = Country::find($id);
        // $citites=City::all();
        $citites = City::where('country_id', $country->id)->get();
        if ($country) {
            foreach ($citites as $city) {
                $city->delete();
            }
            $country->delete();
            return response()->json(['message' => 'Deleted successfully']);
        }
    }

    // city
    public function city()
    {
        return view('backend.modules.setting.city');
    }



    public function createCity(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name' => 'City name is Required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $city = new City();
        $city->country_id = $request->country_id;
        $city->name = $request->name;
        $city->own_name = $request->own_name;
        $city->save();

        return response()->json([
            'message' => 'Saved Successfully',
        ], 201);
    }


    public function cityList()
    {
        // Fetch all cities along with their associated countries to avoid N+1 query problem
        $cities = City::with('country')->get();

        $cityList = [];

        // Iterate through each city to build the city list
        foreach ($cities as $city) {
            $cityList[] = [
                'id' => $city->id ?? '',
                'name' => $city->name ?? '',
                'country_name' => $city->country->name ?? '',
                'own_name' => $city->own_name ?? '',
            ];
        }

        // Return the city list as a JSON response
        return response()->json([
            'data' => $cityList
        ]);
    }

    public function singleCity($id)
    {

        $data = City::find($id);

        return response()->json([
            'data' => $data
        ]);
    }

    public function cityUpdate(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name' => 'City name is Required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $city = City::find($request->id);
        $city->country_id = $request->country_id;
        $city->name = $request->name;
        $city->own_name = $request->own_name;
        $city->save();

        return response()->json([
            'message' => 'Saved Successfully',
        ], 201);
    }

    public function cityDestroy($id)
    {
        // Find the city by its ID
        $city = City::find($id);

        // Check if the city exists
        if (!$city) {
            return response()->json(['message' => 'City not found'], 404);
        }

        // Retrieve all upazilas related to the city
        $upazilas = Upazila::where('district_id', $city->id)->get();

        // Check if there are any upazilas and delete them
        if ($upazilas->isNotEmpty()) {
            foreach($upazilas as $upazila) {
                $upazila->delete();
            }
        }

        // Delete the city
        $city->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
    // upazila
    public function upazila()
    {
        return view('backend.modules.setting.upazila');
    }
    public function upazilaList()
    {
        // Fetch all cities along with their associated countries to avoid N+1 query problem
        $upazilas = Upazila::with('country', 'city')->orderBy('id','desc')->get();

        $upazilaList = [];

        // Iterate through each city to build the city list
        foreach ($upazilas as $upazila) {
            $upazilaList[] = [
                'id' => $upazila->id ?? '',
                'name' => $upazila->name ?? '',
                'country_name' => $upazila->country->name ?? '',
                'city_name' => $upazila->city->name ?? '',
            ];
        }

        // Return the city list as a JSON response
        return response()->json([
            'data' => $upazilaList
        ]);
    }
    public function storeUpazila(Request $request)
    {

        $rules = [
            'name' => 'required',
            'city_id' => 'required',

        ];
        $messages = [
            'name' => 'Upazila name is Required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $country = City::where('id', $request->city_id)->first();

        $review = new Upazila();
        $review->name = $request->name;
        $review->country_id = $country->country_id;
        $review->district_id  = $request->city_id;
        $review->save();
        return response()->json([
            'message' => 'Saved Successfully',
        ], 201);
    }
    public function updateUpazila(Request $request, $id)
    {

        $rules = [
            'name' => 'required',
            'city_id' => 'required',
        ];
        $messages = [
            'name' => 'Upazila name is Required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $country = City::where('id', $request->city_id)->first();

        $review = Upazila::find($id);
        $review->name = $request->name;
        $review->country_id = $country->country_id;
        $review->district_id  = $request->city_id;
        $review->save();
        return response()->json([
            'message' => 'Saved Successfully',
        ], 201);
    }
    public function singleUpazila($id)
    {

        $data = Upazila::find($id);

        return response()->json([
            'data' => $data
        ]);
    }

    public function upazilaDestroy($id){

        $upazila = Upazila::find($id);
        if ($upazila) {

            $upazila->delete();
            return response()->json(['message' => 'Deleted successfully']);
        }

    }
}
