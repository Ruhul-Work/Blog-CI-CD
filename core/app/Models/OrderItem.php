<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;


class OrderItem extends Model
{
    use HasFactory,SoftDeletes;



    protected $table = 'orders_items';

    protected $fillable = [
        'order_id',
        'order_number',
        'product_id',
        'category_id',
        'subcategory_id',
        'author_id',
        'seller_id',
        'publisher_id',
        'qty',
        'price',
        'total',
        'campaign_id',
        'status',
    ];



    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function publisher()
    {
        return $this->belongsTo(Publisher::class,'publisher_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
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




    protected static function boot()

    {
        parent::boot();

        // Creating event
//        static::creating(function ($model) {
//            $model->created_by = Auth::id();
//        });

        // Updating event
//        static::updating(function ($model) {
//            $model->updated_by = Auth::id();
//        });
    }

    // public function campaignProduct()
    // {
    //     return $this->belongsTo(CampaignProduct::class, 'campaign_id');
    // }



}
