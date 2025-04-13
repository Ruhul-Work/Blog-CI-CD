<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function searchNav()
    {
        // Retrieve all enum values from the Product model
        $productTypes = Product::getPossibleEnumValues('products','product_type');

        return view('frontend.includes.header', [
            'productTypes' => $productTypes,
        ]);
    }
}
