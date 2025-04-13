<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class HomeCategory extends Model
{
    use HasFactory;

    protected $table = 'home_categories';

    protected $fillable = ['name', 'created_by', 'updated_by', 'deleted_by'];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_sections', 'home_category_id', 'category_id')
            ->withPivot('id', 'sort_order');
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'author_sections', 'home_author_id', 'author_id')
            ->withPivot('id', 'sort_order');
    }

    public function publishers()
    {
        return $this->belongsToMany(Publisher::class, 'publisher_sections', 'home_publisher_id', 'publisher_id')
            ->withPivot('id', 'sort_order');
    }

    public function reviews()
    {
        return $this->belongsToMany(Review::class, 'review_sections', 'home_review_id', 'review_id')
            ->withPivot('id', 'sort_order');
    }

    protected static function boot()
    {
        parent::boot();
        // Creating event
        static::creating(function ($model) {
            $userId = Auth::id();
            Log::info('Creating HomeCategory: Auth ID', ['user_id' => $userId]);
            $model->created_by = $userId;
        });

        // Updating event
        static::updating(function ($model) {
            $userId = Auth::id();
            Log::info('Updating HomeCategory: Auth ID', ['user_id' => $userId]);
            $model->updated_by = $userId;
        });

        // Deleting event
        static::deleting(function ($model) {
            $userId = Auth::id();
            Log::info('Deleting HomeCategory: Auth ID', ['user_id' => $userId]);
            $model->deleted_by = $userId;
            $model->save(); // Ensure to save the updated deleted_by value
        });
    }
}
