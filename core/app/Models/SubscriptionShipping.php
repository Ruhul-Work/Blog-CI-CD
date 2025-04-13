<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SubscriptionShipping extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subscription_shippings';

    protected $fillable = [
        'order_number',
        'subscription_order_id',
        'user_id',
        'name',
        'mobile_number',
        'address',
        'start_date',
        'end_date',
    ];

    public function order()
    {
        return $this->belongsTo(SubscriptionOrder::class, 'subscription_order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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

}
