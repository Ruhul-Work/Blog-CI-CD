<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;
    protected $table = 'blog_categories';

    protected $fillable = [
        'blog_id',
        'category_id',
    ];


    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }

}
