<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class OrderTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_transactions';

    protected $fillable = [
        'order_number',
        'order_id',
        'user_id',
        'method_id',
        'method_name',
        'transaction_id',
        'amount',
        'status',
        'verify_by',
        'notes',
        'is_posted',
        'created_by',
        'updated_by',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
             if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->verify_by = Auth::id();
            } else {
                // Use the order's user_id if the user is not authenticated
                $model->created_by = $model->user_id;
                
            }
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }


     public function paymentMethod()
     {
         return $this->belongsTo(PaymentMethod::class, 'method_id');
     }


}
