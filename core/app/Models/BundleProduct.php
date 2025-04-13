<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'bundle_product_id',
        'name',
        'current_price',
        'quantity',
        'total',

    ];
}
