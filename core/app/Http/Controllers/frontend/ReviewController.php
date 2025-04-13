<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ReviewController extends Controller
{

    public function submitReview(Request $request, $productId)
    {

        if (!Auth::check()) {
            return response()->json(['message' => 'Please log in first to write review'], 401);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'name' => 'required|string|max:255',
            'comment' => 'required|string|max:1000',
        ]);

        $review = new Review();
        $review->product_id = $productId;
        $review->rating = $validated['rating'];
        $review->name = $validated['name'];
        $review->comment = $validated['comment'];
        $review->user_id = Auth::id() ?: null;
        $review->save();

        $review->load('user');

        $html = View::make('frontend.modules.product.review_list', compact('review'))->render();


        return response()->json(['html' => $html]);
    }





}
