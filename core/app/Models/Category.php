<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';

    protected $fillable = ['name', 'slug', 'icon', 'cover_image', 'meta_image', 'description', 'meta_title', 'meta_description', 'status','is_menu', 'created_by','updated_by','deleted_by'];

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




    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }


    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_categories', 'category_id', 'blog_id');
    }
    

    public function blogCategory(){

        return $this->belongsToMany(BlogCategory::class, 'blog_categories','blog_id','category_id');

    }

    public function homeCategory()
    {
        return $this->belongsToMany(HomeCategory::class, 'category_sections','home_category_id','category_id');
    }
}
