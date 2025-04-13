<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Author extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'authors';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'icon',
        'cover_image',
        'meta_title',
        'meta_description',
        'meta_image',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


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
            $model->save();
        });
    }

    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'author_product');
    }

    public function homeCategory()
    {
        return $this->belongsToMany(HomeCategory::class, 'author_sections','home_author_id','author_id');
    }

}
