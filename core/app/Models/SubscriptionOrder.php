<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SubscriptionOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subscription_orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'subscription_package_id',
        'package_price',
        'discount',
        'coupon_id',
        'coupon_discount',
        'subtotal',
        'quantity',
        'total',
        'pay_method',
        'pay_amount',
        'payment_status',
        'subscription_start_date',
        'end_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class, 'subscription_package_id');
    }

    public function items()
    {
        return $this->hasMany(SubscriptionItem::class, 'subscription_order_id');
    }

    public function shipping()
    {
        return $this->hasOne(SubscriptionShipping::class, 'subscription_order_id');
    }

    /**
     * Boot method to handle created_by, updated_by, and deleted_by fields
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically assign user on create/update/delete
        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        static::deleting(function ($model) {
            $model->deleted_by = Auth::id();
            $model->save();
        });
    }


    protected static function generateOrderNumber()
    {
        $lastOrderId = static::withTrashed()->max('id');
        $newOrderId = $lastOrderId + 1;
        $orderNumber = 'EMBlog' . date('dmy') . $newOrderId;

        return $orderNumber;
    }
}
