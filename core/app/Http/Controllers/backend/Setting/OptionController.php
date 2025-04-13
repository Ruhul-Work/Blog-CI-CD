<?php

namespace App\Http\Controllers\backend\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;

class OptionController extends Controller
{
    public function index()
    {
        return view('backend.modules.setting.option.index');
    }
    public function basic()
    {
        return view('backend.modules.setting.option.basic');
    }
    public function website()
    {

        $orderSettings = Option::where('type', 'Website')->get();
        $settingOrder = [];
        foreach ($orderSettings as $order) {
            $settingOrder[$order->name] = $order->value;
        }
        extract($settingOrder);
        return view('backend.modules.setting.option.website', compact(array_keys($settingOrder)));
    }

    public function websiteStore(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $name => $value) {
            if ($request->hasFile($name) && $request->file($name)->isValid()) {

                $validatedData = $request->validate([
                    $name => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                ]);

                $path = 'uploads/setting/';

                if ($name === 'icon') {

                    $path .= date('Y/m/d/');

                } elseif ($name === 'logo') {

                    $path .= 'logo/' . date('Y/m/d/');

                }elseif($name === 'meta_image'){

                    $path .= 'meta_image/' . date('Y/m/d/');

                }
                $imageName = uniqid() . '.' . $request->file($name)->extension();

                $request->file($name)->move($path, $imageName);

                // Update database with file path
                $setting = Option::where('type', 'Website')->where('name', $name)->first();

                if ($setting) {

                    $setting->value = $path . $imageName;

                } else {
                    $setting = new Option();
                    $setting->type = 'Website';
                    $setting->name = $name;
                    $setting->value = $path . $imageName;
                }
                $setting->save();
            } else {
                // Handle non-file values (if any)
                $setting = Option::where('type', 'Website')->where('name', $name)->first();
                if ($setting) {
                    $setting->value = $value;
                } else {
                    $setting = new Option();
                    $setting->type = 'Website';
                    $setting->name = $name;
                    $setting->value = $value;
                }
                $setting->save();
            }
        }

        return response()->json([
            'message' => 'Website settings saved successfully!'
        ]);
    }

    public function social()
    {

        $orderSettings = Option::where('type', 'Social')->get();
        $settingOrder = [];
        foreach ($orderSettings as $order) {
            $settingOrder[$order->name] = $order->value;
        }
        extract($settingOrder);

        return view('backend.modules.setting.option.social', compact(array_keys($settingOrder)));
    }
    public function socialStore(Request $request)
    {
        $data = $request->except('_token');
        $invalidUrls = [];
        $socialPatterns = [
            'facebook' => '/^(https?:\/\/)?(www\.)?facebook.com\/[a-zA-Z0-9(\.\?)?]/',
            // 'twitter' => '/^(https?:\/\/)?(www\.)?twitter.com\/[a-zA-Z0-9(\.\?)?]/',
            'instagram' => '/^(https?:\/\/)?(www\.)?instagram.com\/[a-zA-Z0-9(\.\?)?]/',
            // 'linkedin' => '/^(https?:\/\/)?(www\.)?linkedin.com\/[a-zA-Z0-9(\.\?)?]/',
            'youtube' => '/^(https?:\/\/)?(www\.)?youtube.com\/[a-zA-Z0-9(\.\?)?]/',

        ];

        foreach ($data as $name => $value) {

            $isValid = false;
            foreach ($socialPatterns as $key => $pattern) {
                if (strpos($name, $key) !== false) {
                    if (preg_match($pattern, $value)) {
                        $isValid = true;
                        break;
                    }
                }
            }

            if (!$isValid) {
                $invalidUrls[] = $name;
                continue;
            }

            $setting = Option::where('type', 'Social')->where('name', $name)->first();

            if ($setting) {
                $setting->value = $value;
            } else {
                $setting = new Option();
                $setting->type = 'Social';
                $setting->name = $name;
                $setting->value = $value;
            }

            $setting->save();
        }

        if (!empty($invalidUrls)) {
            return response()->json([
                'message' => 'Some values are not valid social media URLs',
                'invalid_urls' => $invalidUrls
            ], 422);
        }

        return response()->json([
            'message' => 'Saved successfully!'
        ]);
    }
    public function websiteCore()
    {
        $orderSettings = Option::where('type', 'Core')->get();
        $settingOrder = [];
        foreach ($orderSettings as $order) {
            $settingOrder[$order->name] = $order->value;
        }
        extract($settingOrder);

        return view('backend.modules.setting.option.core', compact(array_keys($settingOrder)));
    }
    public function websiteCoreStore(Request $request)
    {
        $data = $request->except('_token');
        // dd($data);

        foreach ($data as $name => $value) {
            $setting = Option::where('type', 'Core')->where('name', $name)->first();
            if ($setting) {
                $setting->value = $value;
            } else {
                $setting = new Option();
                $setting->type = 'Core';
                $setting->name = $name;
                $setting->value = $value;
            }

            $setting->save();
        }

        return response()->json([
            'message' => 'Core settings saved successfully!'
        ]);
    }
    public function Order()
    {
        // Fetch all settings of type 'Order'
        $orderSettings = Option::where('type', 'Order')->get();

        // Initialize an empty array to store the settings
        $settingOrder = [];

        // Loop through each setting and store the name-value pair in the array
        foreach ($orderSettings as $order) {
            $settingOrder[$order->name] = $order->value;
        }

        // Extract the associative array to individual variables
        extract($settingOrder);

        // Pass the individual variables to the view using compact
        return view('backend.modules.setting.option.order', compact(array_keys($settingOrder)));
    }
    public function orderSettingStore(Request $request)
    {
        $data = $request->except('_token'); // Exclude the _token field

        foreach ($data as $name => $value) {

            $setting = Option::where('type', 'Order')->where('name', $name)->first();

            if ($setting) {
                $setting->value = $value;
            } else {
                $setting = new Option();
                $setting->type = 'Order';
                $setting->name = $name;
                $setting->value = $value;
            }
            $setting->save();
        }

        return response()->json([
            'message' => 'Order settings saved successfully!'
        ]);
    }
    public function email()
    {
        $orderSettings = Option::where('type', 'Email')->get();
        $settingOrder = [];
        foreach ($orderSettings as $order) {
            $settingOrder[$order->name] = $order->value;
        }
        extract($settingOrder);
        return view('backend.modules.setting.option.email', compact(array_keys($settingOrder)));
    }
    public function emailSettingStore(Request $request)
    {
        $data = $request->except('_token');
        // dd($data);

        foreach ($data as $name => $value) {

            $setting = Option::where('type', 'Email')->where('name', $name)->first();

            if ($setting) {
                $setting->value = $value;
            } else {
                $setting = new Option();
                $setting->type = 'Email';
                $setting->name = $name;
                $setting->value = $value;
            }
            $setting->save();
        }

        return response()->json([
            'message' => 'Email settings saved successfully!'
        ]);
    }
}
