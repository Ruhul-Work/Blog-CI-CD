<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSubmenu extends Model
{
    use HasFactory;

    protected $table='menu_submenus';

    protected $fillable=['menu_id','submenu_id'];
}
