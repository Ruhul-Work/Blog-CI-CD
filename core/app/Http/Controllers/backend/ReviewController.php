<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Review;


class ReviewController extends Controller
{
    //
    public function index()
    {
        return view('backend.modules.review.index');
    }
    //
    public function storeReview(Request $request)
    {
        $rules = [
            'name' => 'required',
            'comment' => 'required',

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

        $review = new Review();
        $review->name = $request->name;
        $review->comment = $request->comment;
        // Set other attributes here following the same pattern
        if ($request->hasFile('image')) {
            $path = 'uploads/review/image/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('image')->move($path, $imageName);
            $review->image = $path . $imageName;
        }
        $review->save();

        return response()->json([
            'message' => 'Saved Successfully',
        ], 201);
    }
    //
    public function allReviews()
    {

        $data = Review::orderBy('created_at', 'desc')->get();

        return response()->json([

            'data' => $data

        ]);
    }

    public function singleReview($id)
    {

        $data = Review::find($id);

        return response()->json([
            'data' => $data
        ]);
    }

    public function reviewUpdate(Request $request)
    {
        $rules = [
            'name' => 'required',
            'comment' => 'required',
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

        $review =Review::find($request->id);
        $review->name = $request->name;
        $review->comment = $request->comment;
        // Set other attributes here following the same pattern
        if ($request->hasFile('image')) {

            if (isset($review->image)) {
                $oldImagePath = $review->image;
                unlink($oldImagePath);
            }

            $path = 'uploads/review/image/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('image')->move($path, $imageName);
            $review->image = $path . $imageName;
        }
        $review->save();

        return response()->json([
            'message' => 'Saved Successfully',
        ], 201);
    }

    public function reviewDestroy($id){
        
        $review = Review::find($id);
        if ($review) {
            if (isset($review->image) && file_exists($review->image)) {
                unlink($review->image);
            }
            $review->delete();
        return response()->json(['message' => 'Deleted successfully']);

    }
}
}
