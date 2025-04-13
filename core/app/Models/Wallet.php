<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'transaction_type', 'amount', 'w_type',
        'order_id', 'return_id', 'payment_method_id', 'note',
        'status', 'created_by', 'updated_by'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relationship with Customer
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relationship with Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
     // Accessor for w_type
     public function getWTypeAttribute($value)
     {
         return strtoupper($value); // Apply custom logic (e.g., convert to uppercase)
     }
}
