<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\SectionProduct;

class ProductSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'section_type',
        'link',
        'sort_order',
        'created_by',
        'updated_by',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    // section product
    public function Products()
    {
        return $this->belongsTo(SectionProduct::class, 'product_id', 'id');
    }

     
       public function sectionProducts()
       {
           return $this->belongsToMany(Product::class, 'section_products', 'section_id', 'product_id')
               ->withPivot('sort_order')
               ->orderBy('pivot_sort_order', 'asc');
       }

    protected static function boot()
    {

        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
}
