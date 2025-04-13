<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageOrderTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'package_order_transactions';

    protected $fillable = [
        'order_number',
        'subscription_order_id',
        'user_id',
        'method_id',
        'method_name',
        'transaction_id',
        'payment_id',
        'customerMsisdn',
        'amount',
        'status',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(SubscriptionOrder::class, 'subscription_order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'method_id');
    }
}
