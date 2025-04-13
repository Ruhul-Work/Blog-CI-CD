<?php

namespace App\Services;

use App\Models\Menu;

class MenuService
{
    public function getMenuItems()
    {

        return Menu::where('status',1)
            ->orderBy('sort_order')
            ->get();
    }
}
