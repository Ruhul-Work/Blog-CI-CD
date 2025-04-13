<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ReturnProduct;

class ReturnItem extends Model
{

    use HasFactory,SoftDeletes;

    protected $fillable = [
        'return_id',
        'return_number',
        'product_id',
        'category_id',
        'subcategory_id',
        'author_id',
        'seller_id',
        'publisher_id',
        'qty',
        'price',
        'total',
        'status',
    ];

    public function returns()
    {
        return $this->belongsTo(ReturnProduct::class, 'return_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function publisher()
    {
        return $this->belongsTo(Publisher::class,'publisher_id');
    }

    public function authors() {
        return $this->belongsToMany(Author::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function subcategories() {
        return $this->belongsToMany(Subcategory::class);
    }

    public function setAuthorIdAttribute($value) {
        $this->attributes['author_id'] = json_encode($value);
    }

    public function getAuthorIdAttribute($value) {
        return json_decode($value, true);
    }

    public function setCategoryIdAttribute($value) {

        $this->attributes['category_id'] = json_encode($value);
    }

    public function getCategoryIdAttribute($value) {

        return json_decode($value, true);
    }

    public function setSubcategoryIdAttribute($value) {
        $this->attributes['subcategory_id'] = json_encode($value);
    }

    public function getSubcategoryIdAttribute($value) {
        return json_decode($value, true);
    }
}
