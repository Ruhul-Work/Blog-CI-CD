<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PurchaseTransaction extends Model
{
    use HasFactory;


    protected $fillable = [
        'purchase_number',
        'purchase_id',
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



    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }


    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'method_id');
    }


    protected static function booted()
    {
        static::creating(function ($model) {
            $model->verify_by= Auth::id();
            $model->created_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
}
