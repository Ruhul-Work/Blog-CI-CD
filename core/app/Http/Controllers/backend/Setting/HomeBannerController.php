<?php

namespace App\Http\Controllers\backend\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\HomeBanner1;
use Illuminate\Support\Facades\Auth;
use App\Models\HomeBanner2;



class HomeBannerController extends Controller
{

    public function index()
    {

        return view('backend.modules.homebanner.index');
    }

    public function index2()
    {

        return view('backend.modules.homebanner.index2');
    }
    // for api store
    public function homeStore(Request $request)
    {
        // Define validation rules and custom messages
        $rules = [
            'name' => 'required',
            'image' => 'required',
            'status' => 'required|in:0,1',

        ];

        $messages = [
            'name.required' => 'Name is required',
            'image.required' => 'image is required',
            'status.required' => 'Status is required',


        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new HomeCart1 instance and assign values from the request
        $hcart = new HomeBanner2();
        $hcart->name = $request->name;
        $hcart->status = $request->status;
        $hcart->link = $request->link;

        $hcart->created_by = Auth::id();
        $hcart->updated_by = Auth::id();

        if ($request->hasFile('image')) {
            // $path = 'uploads/homecart/image/' . date('Y/m/d') . '/';
            // $imageName = uniqid() . '.webp';
            // $request->file('image')->move($path, $imageName);
            // $hcart->image = $path . $imageName;
            $hcart->image = uploadImage($request->file('image'), 'homecart/image', '0', 60);
        }

        // Save the model to the database
        $hcart->save();

        // Return success response
        return response()->json([
            'message' => "Home Banner-2 Created Successfully",
            'data' => $hcart
        ], 201); // Use 201 status code for resource creation
    }
    public function store(Request $request)
    {

        // Define validation rules and custom messages
        $rules = [
            'name' => 'required',
            'image' => 'required',
            'status' => 'required|in:0,1',
            // 'link' => 'required|url',
        ];

        $messages = [
            'name.required' => 'Name is required',
            'image.required' => 'image is required',
            'status.required' => 'Status is required',
            'link.required' => 'Link is required',
            // 'link.url' => 'Link must be a valid URL',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new HomeCart1 instance and assign values from the request
        $hcart = new HomeBanner1();
        $hcart->name = $request->name;
        $hcart->status = $request->status;
        $hcart->link = $request->link;

        $hcart->created_by = Auth::id();
        $hcart->updated_by = Auth::id();

        if ($request->hasFile('image')) {

            // $path = 'uploads/homecart/image/' . date('Y/m/d') . '/';
            // $imageName = uniqid() . '.webp';
            // $request->file('image')->move($path, $imageName);
            // $hcart->image = $path . $imageName;

            $hcart->image = uploadImage($request->file('image'), 'homecart/image', '0', 60);
        }

        // Save the model to the database
        $hcart->save();

        // Return success response
        return response()->json([
            'message' => "Home Cart Created Successfully",
            'data' => $hcart
        ], 201); // Use 201 status code for resource creation
    }

    // for fetch api

    public function viewHomeCart()
    {

        $data = HomeBanner1::orderBy('created_at', 'desc')->get();

        return response()->json([

            'data' => $data

        ]);
    }
    public function viewHomeBanner()
    {

        $data = HomeBanner2::orderBy('created_at', 'desc')->get();

        return response()->json([

            'data' => $data

        ]);
    }

    public function edit($id)
    {

        $data = HomeBanner1::find($id);



        return response()->json([
            'data' => $data
        ]);
    }
    public function editHomeBanner($id)
    {

        $data = HomeBanner2::find($id);



        return response()->json([
            'data' => $data
        ]);
    }

    public function editStore(Request $request, $id)
    {


        // Define validation rules and custom messages
        $rules = [
            'name' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // ensure it's an image file
            'status' => 'required|integer', // added integer validation for status
            // 'link' => 'required|url',
        ];

        $messages = [
            'status.required' => 'Status is required',
            'link.required' => 'Link is required',
            // 'link.url' => 'Link must be a valid URL',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new HomeCart1 instance and assign values from the request
        $hcart = HomeBanner1::find($id);
        $hcart->name = $request->name;
        $hcart->status = $request->status;

        $hcart->link = $request->link;

        $hcart->created_by = Auth::id();
        $hcart->updated_by = Auth::id();

        if ($request->hasFile('image')) {


            // if (isset($request->image)) {
            //     $oldImagePath = $request->image;
            //     unlink($oldImagePath);
            // }

            if (isset($hcart->image) && file_exists($hcart->image)) {
                unlink($hcart->image);
            }

            // $path = 'uploads/homecart/image/' . date('Y/m/d') . '/';
            // $imageName = uniqid() . '.webp';
            // $request->file('image')->move($path, $imageName);
            // $hcart->image = $path . $imageName;
            $bannerImage= uploadImage($request->file('image'), 'homecart/image', '0', 60);
             $hcart->image=$bannerImage;
        }

       



        if ($request->hasFile('meta_image')) {
            // if (isset($request->image)) {
            //     $oldImagePath = $request->image;
            //     unlink($oldImagePath);
            // }
            // $path = 'uploads/homecart/image/' . date('Y/m/d') . '/';
            // $imageName = uniqid() . '.webp';
            // $request->file('image')->move($path, $imageName);
            // $hcart->image = $path . $imageName;
            $bannerMetaImage= uploadImage($request->file('meta_image'), 'homecart/meta_image', '0', 60);
            $hcart->meta_image=$bannerMetaImage;
        }
        

        // Save the model to the database
        $hcart->save();

        // Return success response
        return response()->json([
            'message' => "Home Cart Updated Successfully",
            'data' => $hcart
        ], 201); // Use 201 status code for resource creation
    }

    public function editStoreHomeBanner(Request $request, $id)
    {


        // Define validation rules and custom messages
        $rules = [
            'name' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // ensure it's an image file
            'status' => 'required|integer', // added integer validation for status
            // 'link' => 'required|url',
        ];

        $messages = [
            'status.required' => 'Status is required',
            'link.required' => 'Link is required',
            // 'link.url' => 'Link must be a valid URL',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new HomeCart1 instance and assign values from the request
        $hcart = HomeBanner2::find($id);
        $hcart->name = $request->name;
        $hcart->status = $request->status;
        $hcart->link = $request->link;

        $hcart->created_by = Auth::id();
        $hcart->updated_by = Auth::id();

        if ($request->hasFile('image')) {


            if (isset($hcart->image) && file_exists($hcart->image)) {
                unlink($hcart->image);
            }


  
            
            $bannerImage= uploadImage($request->file('image'), 'homecart/image', '0', 60);
             $hcart->image=$bannerImage;

        }

        if ($request->hasFile('meta_image')) {
            if (isset($request->image)) {
                $oldImagePath = $request->image;
                unlink($oldImagePath);
            }
        
            $bannerMetaImage = uploadImage($request->file('meta_image'), 'homecart/image', '0', 60);
             $hcart->image=$bannerMetaImage;
        }
       

        // Save the model to the database
        $hcart->save();

        // Return success response
        return response()->json([
            'message' => "Home Cart Updated Successfully",
            'data' => $hcart
        ], 201); // Use 201 status code for resource creation
    }

    //destroy api
    public function destroy(Request $request, $id)
    {
        // $Id = decrypt($id);
        $hCart = HomeBanner1::find($id);

        if ($hCart) {

            if (isset($hCart->image) && file_exists($hCart->image)) {
                unlink($hCart->image);
            }

            $hCart->delete();
        }

        return response()->json(['message' => 'Deleted successfully']);
    }
    public function homeBannerDestroy(Request $request, $id)
    {
        // $Id = decrypt($id);
        $hCart = HomeBanner2::find($id);

        if ($hCart) {

            if (isset($hCart->image) && file_exists($hCart->image)) {
                unlink($hCart->image);
            }

            $hCart->delete();
        }

        return response()->json(['message' => 'Deleted successfully']);
    }
}
