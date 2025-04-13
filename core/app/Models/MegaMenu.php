<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MenuMegaCombine;

class MegaMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
    ];

    public function megamenu()
    {
        return $this->belongsToMany(MenuMegaCombine::class, 'menu_mega_combines','menu_id','mega_menu_id');
    }
}
