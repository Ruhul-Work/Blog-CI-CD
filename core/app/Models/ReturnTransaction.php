<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentMethod;
use App\Models\ReturnProduct;

class ReturnTransaction extends Model
{
    use HasFactory;

    // paymentMethod

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function returns()
    {
        return $this->belongsTo(ReturnProduct::class, 'return_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'method_id');
    }
}
