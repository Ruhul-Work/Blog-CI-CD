<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','name', 'slug', 'icon', 'cover_image', 'meta_image', 'description', 'meta_title', 'meta_description', 'status', 'created_by','updated_by','deleted_by'];

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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
