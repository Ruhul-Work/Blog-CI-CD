<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class StationaryController extends Controller
{
    public function index()
    {



        return view('frontend.modules.stationary.index');
    }



    public function getStationaryCategories()
    {
        $categories = Category::where('status', 1)->where('type', 'stationary') ->paginate(18);

        $view = view('frontend.modules.stationary.stationary_category', ['categories' => $categories])->render();

        return response()->json(['html' => $view]);
    }}
