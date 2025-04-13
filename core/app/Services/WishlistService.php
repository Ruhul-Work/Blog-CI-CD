<?php


namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistService
{
    public function getWishlist()
    {
        return Auth::user()->wishlist()->with(['product.authors'])->get();
    }

    public function addToWishlist($productId)
    {
        $user = Auth::user();

        if ($user->wishlist()->where('product_id', $productId)->exists()) {
            return ['message' => 'পণ্য ইতিমধ্যেই উইশ লিস্ট তালিকায় আছে', 'status' => 200];
        }

        $user->wishlist()->create(['product_id' => $productId]);

        return ['message' => 'পণ্য উইশ লিস্ট তালিকা যোগ করা হয়েছে', 'status' => 201];
    }

    public function removeFromWishlist($productId)
    {
        $user = Auth::user();

        $user->wishlist()->where('product_id', $productId)->delete();

        return ['message' => 'উইশ লিস্ট তালিকা থেকে পণ্য সরানো হয়েছে', 'status' => 200];
    }



    public function countWishlistItems()
    {
        $wishlistQuery = Auth::user()->wishlist();

        if ($wishlistQuery->exists()) {
            return $wishlistQuery->count();
        }

        return 0; // If no wishlist items exist, return 0
    }

}
