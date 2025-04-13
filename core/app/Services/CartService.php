<?php


namespace App\Services;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{


//    public function addToCart($productId, $quantity)
//    {
//        // Find the product by its ID
//        $product = Product::find($productId);
//
//        // Check if the product exists
//        if (!$product) {
//            throw new \Exception("পণ্য পাওয়া যায়নি ");
//        }
//
//        // Check stock status
//        if ($product->stock_status == 'upcoming' || $product->stock_status == 'out_of_stock') {
//            throw new \Exception("আপনি আউট অফ স্টক বা আসন্ন পণ্য কিনতে পারবেন না.");
//        }
//
//        // Calculate product discount using a helper function
//        $discountInfo = calculateDiscount($product);
//
//        // Get the current cart items from the session
//        $cartItems = $this->getCart() ?? [];
//        $maxQuantity = (int) get_option('max_order'); // Ensure it's an integer
//        $existingItem = $this->getCartItem($productId);
//
//        // Calculate the new quantity for the product
//        $newQuantity = $quantity;
//        if ($existingItem) {
//            $newQuantity += $existingItem['quantity'];
//        }
//
//        // Check if the new quantity exceeds the maximum allowed quantity
//        if ($newQuantity > $maxQuantity) {
//            throw new \Exception("আপনি একসাথে সর্বোচ্চ {$maxQuantity} ইউনিট ক্রয় করতে পারবেন।");
//        }
//
//        // Check if the product already exists in the cart
//        $found = false;
//        foreach ($cartItems as &$item) {
//            if ($item['id'] == $productId) {
//                // Update quantity and total price for the existing product
//                $item['quantity'] = $newQuantity; // Update quantity
//                $item['total_price'] = priceAfterDiscount($product) * $item['quantity'];
//                $found = true;
//                break;
//            }
//        }
//
//        // If the product is not found in the cart, add it as a new item
//        if (!$found) {
//            $newItem = $this->createCartItem($product, $quantity, $discountInfo);
//            $cartItems[] = $newItem;
//        }
//
//        // Save the updated cart items back to the session
//        session()->put('cart.items', $cartItems);
//    }



    public function addToCart($productId, $quantity)
    {
        $product = Product::with('authors')->find($productId);
        
        //added for security
        Session::forget('couponId');
        Session::forget('couponDiscount');

        if (!$product) {
            throw new \Exception("পণ্য পাওয়া যায়নি ");
        }
        
        
        if (in_array($product->stock_status, ['next_edition', 'upcoming', 'out_of_stock'])) {
    throw new \Exception("এই মুহূর্তে পণ্যটি স্টকে নেই। তবে, আসন্ন পণ্য বা পরবর্তী সংস্করণের জন্য প্রি-অর্ডার নেওয়া হচ্ছে না। অনুগ্রহ করে নতুন আপডেটের জন্য আমাদের সাথে থাকুন।");
}



        // Calculate product discount using a helper function
        $discountInfo = calculateDiscount($product);

        $cartItems = $this->getCart() ?? [];
        
        

        $maxQuantity = (int) get_option('max_order');
        $existingItem = $this->getCartItem($productId);
        

        // If the product already exists in the cart, calculate the new quantity
        if ($existingItem) {
            $newQuantity = $existingItem['quantity'] + $quantity;

            // Validate if the new quantity exceeds the maximum allowed quantity
            if ($newQuantity > $maxQuantity) {
                $remainingQty = $maxQuantity - $existingItem['quantity'];
                if ($remainingQty <= 0) {
                    throw new \Exception("আপনি সর্বাধিক {$maxQuantity} ইউনিট ইতিমধ্যেই কার্টে যোগ করেছেন।");
                }
                // Adjust the quantity to the remaining allowed quantity
                $quantity = $remainingQty;
            }

            // Update the existing item with the new quantity
            foreach ($cartItems as &$item) {
                if ($item['id'] == $productId) {
                    $item['quantity'] = $existingItem['quantity'] + $quantity;
                    $item['total_price'] = priceAfterDiscount($product) * $item['quantity'];
                    break;
                }
            }
          
        } else {
            if ($quantity > $maxQuantity) {
                throw new \Exception("আপনি একসাথে সর্বোচ্চ {$maxQuantity} ইউনিট ক্রয় করতে পারবেন।");
            }
         
            // Add the product as a new item to the cart
            $newItem = $this->createCartItem($product, $quantity, $discountInfo);
           
            $cartItems[] = $newItem;
        }


        session()->put('cart.items', $cartItems);
        
    }

    private function createCartItem($product, $quantity, $discountInfo)
    {
        //dd($product);
        return [
            'slug' => $product->slug,
            'id' => $product->id,
            'quantity' => $quantity,
             'authors' => $product->authors()->exists() ? $product->authors->toArray() : null,
            'publisher_name' => $product->publisher->name??"Unknown",
            'weight' => $product->weight,
            'thumb_image' => $product->thumb_image,
            'bangla_name' => $product->bangla_name,
            'english_name' => $product->english_name,
            'mrp_price' => $product->mrp_price,
            'current_price' => priceAfterDiscount($product),
            'discount_amount' => $discountInfo['discountAmount'],
            'discount_percentage' => $discountInfo['discountPercentage'],
            'total_price' => priceAfterDiscount($product) * $quantity,
        ];
    }










    public function updateCart($productId, $quantity)
    {
         //added for security
        Session::forget('couponId');
        Session::forget('couponDiscount');
        
        $maxQuantity = get_option('max_order');

        if ($quantity > $maxQuantity) {
            throw new \Exception("আপনি একসাথে সর্বোচ্চ অনুমোদিত পরিমাণ ($maxQuantity) ইউনিট ক্রয় করতে পারবেন .");
        }

        $cartItems = $this->getCart();

        foreach ($cartItems as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] = $quantity;
                $item['total_price'] = $item['current_price'] * $quantity;
                break;
            }
        }
        session()->put('cart.items', $cartItems);
    }




//    public function updateCart($productId, $quantity)
//    {
//
//        $cartItems = $this->getCart();
//
//         foreach ($cartItems as &$item) {
//
//             if ($item['id'] == $productId) {
//
//                 $item['quantity'] =$quantity;
//
//                 $item['total_price'] = $item['current_price'] * $quantity;
//                 break;
//             }
//         }
//         session()->put('cart.items', $cartItems);
//    }




    public function removeFromCart($productId)
    {
         //added for security
        Session::forget('couponId');
        Session::forget('couponDiscount');
        
        $cartItems = $this->getCart();

        $cartItems = array_filter($cartItems, function($item) use ($productId) {

            return $item['id'] != $productId;
        });

        session()->put('cart.items', $cartItems);
    }


   public function getCart()
    {

        $this->refreshCartPrices();
        return session()->get('cart.items', []);
    }
    
    
     public function refreshCartPrices()
    {
        // Retrieve the cart items from the session
        $cartItems = session()->get('cart.items', []);

        // Loop through each cart item to check and update the price if necessary
        foreach ($cartItems as &$item) {
            // Fetch the latest product information from the database
            $product = Product::with('authors', 'publisher')->find($item['id']);

            if ($product) {
                // Calculate the discount using the same helper function
                $discountInfo = calculateDiscount($product);
                $newPrice = priceAfterDiscount($product);

                // If the price has changed, update the item details in the cart
                if ($item['current_price'] != $newPrice) {
                    $item['slug'] = $product->slug;
                    $item['id'] = $product->id;
                    $item['quantity'] = $item['quantity']; // Keep the existing quantity
                    $item['authors'] = $product->authors()->exists() ? $product->authors->toArray() : null;
                    $item['publisher_name'] = $product->publisher->name ?? "Unknown";
                    $item['thumb_image'] = $product->thumb_image;
                    $item['bangla_name'] = $product->bangla_name;
                    $item['english_name'] = $product->english_name;
                    $item['mrp_price'] = $product->mrp_price;
                    $item['current_price'] = $newPrice;
                    $item['discount_amount'] = $discountInfo['discountAmount'];
                    $item['discount_percentage'] = $discountInfo['discountPercentage'];
                    $item['total_price'] = $newPrice * $item['quantity']; // Update total price
                }
            } else {
                // If the product doesn't exist anymore, optionally handle that case
                unset($item); // Optionally remove the item from the cart if the product is no longer available
            }
        }

        // Save the updated cart back to the session
        session()->put('cart.items', $cartItems);

        // Return the updated cart items
        return $cartItems;
    }

    public function getCartItem($productId)
    {
        $cartItems = $this->getCart();

        $item = collect($cartItems)->firstWhere('id', $productId);

        if ($item) {
            return $item;
        }else
        {
            return null;
        }

    }

    public function countItems()
    {

        $cartItems =  $this->getCart();

        return count($cartItems);
    }

    public function getTotal()
    {

        $cartItems =  $this->getCart();
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['total_price'];
        }
        return $total;
    }

    public function clearCart()
    {
        session()->forget('cart.items');
    }




//to calculate to tal discount from cart  items
    public function getDiscount()
    {
        $cartItems = $this->getCart();
        $totalDiscountAmount = 0;

        foreach ($cartItems as $item) {
            $totalDiscountAmount += $item['discount_amount'] * $item['quantity'];
        }

        return [
            'total_discount_amount' => $totalDiscountAmount,
        ];
    }
}
