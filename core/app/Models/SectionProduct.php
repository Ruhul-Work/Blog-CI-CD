<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ProductSection;
use App\Models\Product;

class SectionProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'section_id',
        'product_id',
        'sort_order',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id','id');
    }


    public function productSection()
    {
        return $this->belongsTo(ProductSection::class, 'section_id');
    }
}
