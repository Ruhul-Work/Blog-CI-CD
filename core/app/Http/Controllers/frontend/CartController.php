<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getCart()
    {

        $cartItems = $this->cartService->getCart();
        $total = $this->cartService->getTotal();

        return view('frontend.modules.cart.show_products', [

            'cartItems' => $cartItems,
            'cartTotal' => $total,

        ]);
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);







        try {
            // Add the product to the cart using the service class
            $this->cartService->addToCart($productId, $quantity);

            return response()->json([
                'message' => 'পণ্য সফলভাবে কার্ট যোগ করা হয়েছে.',
            ]);
        } catch (\Exception $e) {
            // Return a JSON response with the error message
            return response()->json(['message' => $e->getMessage()], 400);
        }

    }

    public function updateCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');


        try {
            $this->cartService->updateCart($productId, $quantity);
            $item = $this->cartService->getCartItem($productId);

            // If the item is not found or invalid, return an error
            if (!$item) {
                return response()->json(['message' => 'কার্ট আইটেম পাওয়া যায়নি.'], 404);
            }


            return response()->json([
                'message' => 'কার্ট সফলভাবে আপডেট করা হয়েছে.',
                'item' => $item,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }


    }

    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');


        try {
            $this->cartService->removeFromCart($productId);
            return response()->json([
                'message' => 'পণ্য কার্ট থেকে সফলভাবে সরানো হয়েছে.',

            ]);

        } catch (\Exception $e) {

            return response()->json(['message' => $e->getMessage()], 400);

        }


    }

    public function clearCart()
    {
        $this->cartService->clearCart();
        return response()->json([
            'message' => 'কার্ট সফলভাবে সাফ করা হয়েছে৷.',

        ]);
    }


    public function countItems()
    {
        $count = $this->cartService->countItems();

        return response()->json([ 'count'=>$count]);

    }
    public function getTotal()
    {
        $total = $this->cartService->getTotal();

        return response()->json(['total' => $total]);
    }
}
