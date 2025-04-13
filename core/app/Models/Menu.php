<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\SubMenu;
use App\Models\MenuMegaCombine;
use App\Models\MegaMenu;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sub_menu_id',
        'child_id',
        'icon',
        'menu_type',
        'link',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        // Creating event
        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });
        // Updating event
        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
        // Deleting event
        static::deleting(function ($model) {
            $model->deleted_by = Auth::id();
            $model->save(); // Ensure to save the updated deleted_by value
        });
    }

    public function submenus()
    {
        return $this->belongsToMany(SubMenu::class, 'menu_submenus', 'menu_id', 'submenu_id');
    }


    public function megamenu()
    {
        return $this->belongsToMany(MegaMenu::class, 'menu_mega_combines', 'menu_id', 'mega_menu_id');
    }
}
