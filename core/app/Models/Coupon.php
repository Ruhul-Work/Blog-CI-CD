<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coupons';

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $fillable = [
        'c_type',
        'type_details',
        'title',
        'code',
        'user_id',
        'discount',
        'discount_type',
        'status',
        'start_date',
        'end_date',
        'user_type',
        'stock',
        'individual_max_use',
        'notes',
    ];
    // relation to user
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    public function couponUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_products', 'coupon_id', 'product_id');
    }


    public function isValid($userId)
    {
        $currentDate = now();
    
       
        if ($this->start_date && $this->start_date->greaterThan($currentDate)) {
            return false;
        }
    
        if ($this->end_date && $this->end_date->lessThan($currentDate)) {
            return false; 
        }
    
       
        return true;
    }

}
