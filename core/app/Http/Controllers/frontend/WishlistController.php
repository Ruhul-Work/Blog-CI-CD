<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{

    protected $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    public function getWishlist()
    {
        if(!auth('web')->check()) {

            return redirect()->route('login')->with('warning', 'আপনার ইচ্ছা তালিকা দেখতে লগ ইন করুন.');
        }

        $wishlistItems = $this->wishlistService->getWishlist();


        return view('frontend.modules.wishlist.show_products', compact('wishlistItems'));
    }


    public function addToWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['warning' => 'আপনার পছন্দের তালিকায় পণ্য যোগ করতে প্রথমে লগ ইন করুন'], 401);
        }

        $productId = $request->input('product_id');

        $result = $this->wishlistService->addToWishlist($productId);

        return response()->json(['message' => $result['message']], $result['status']);
    }

    public function removeFromWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $productId = $request->input('product_id');

        $result = $this->wishlistService->removeFromWishlist($productId);

        return response()->json(['message' => $result['message']], $result['status']);
    }


    public function countWishlistItems()
    {
        $count = Auth::check() ? $this->wishlistService->countWishlistItems() : 0;

        return response()->json(['count' => $count], 200);
    }


}
