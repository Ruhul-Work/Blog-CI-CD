<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShipping extends Model
{
    use HasFactory;

    protected $table = 'order_shippings';

    protected $fillable = [

        'order_id',
        'user_id',
        'phone',
        'alternate_phone',
        'country_id',
        'city_id',
        'upazila_id',
        'address',
        'zip_code',

    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

     public function country()
     {
         return $this->belongsTo(Country::class, 'country_id');
     }


    // public function division()
    // {
    //     return $this->belongsTo(Division::class,'division_id');
    // }


    public function city()
     {
         return $this->belongsTo(City::class, 'city_id');
     }


     public function upazila()
     {
         return $this->belongsTo(Upazila::class, 'upazila_id');
     }


    public function union()
    {
        return $this->belongsTo(Union::class,'union_id');
    }
}
