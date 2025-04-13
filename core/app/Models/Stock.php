<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Stock extends Model
{
    use HasFactory,SoftDeletes;

    // protected static function boot()
    // {
    //     parent::boot();

    //     // Creating event
    //     static::creating(function ($stock) {
    //         $stock->created_by = Auth::id();

    //     });

    //     // Updating event
    //     static::updating(function ($stock) {
    //         $stock->updated_by = Auth::id();
    //     });

    // }
    
    
      protected static function boot()
    {
        parent::boot();

        // Creating event
        static::creating(function ($stock) {
            $stock->created_by = Auth::id();

        });
        // Updating event
        static::updating(function ($stock) {
            $stock->updated_by = Auth::id();
        });


        static::saved(function ($stock) {
            self::queueStockUpdate($stock->product_id);
        });

        static::deleted(function ($stock) {
            self::queueStockUpdate($stock->product_id);
        });

    }



    protected static function queueStockUpdate($productId)
    {
        DB::table('pending_stock_updates')->insertOrIgnore(['product_id' => $productId]);
    }



    public function user()
    {
        return $this->belongsTo(User::class, ['updated_by','created_by']);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
