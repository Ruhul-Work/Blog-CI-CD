<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use App\Models\ReturnItem;
use App\Models\ReturnTransaction;
use App\Models\User;
use App\Models\Wallet;

class ReturnProduct extends Model
{
    use HasFactory, SoftDeletes;

      protected $fillable = [
        'id',
        'return_number',
        'customer_id',
        'admin_note',
        'return_date',
        'adjust_amount',
        'discount_amount',
        'packing_charge',
        'tax',
        'shipping_charge',
        'subtotal',
        'quantity',
        'total',
        'payment_status',
        'source',
        'courier_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function boot()
    {
        parent::boot();
        // Creating event
        static::creating(function ($return) {
            $return->created_by = Auth::id();
            $return->return_number = static::generateReturnNumber($return->id);
        });

        // Updating event
        static::updating(function ($return) {
            $return->updated_by = Auth::id();
        });

        static::deleting(function ($purchase) {
        });
        // Deleting event
        static::deleting(function ($return) {
            if ($return->isForceDeleting()) {
                // Handle force deleting
                $return->returnItems()->forceDelete();
                $return->stocks()->forceDelete();
                // $purchase->transactions()->forceDelete();
            } else {
                // Handle soft deleting
                $return->deleted_by = Auth::id();
                $return->save();
                $return->returnItems()->delete();
                $return->stocks()->delete();
                // $purchase->transactions()->delete();
            }
        });
    }


    protected static function generateReturnNumber()
    {
        // Get the last non-soft-deleted order ID from the database
        $lastOrderId = static::withTrashed()->max('id');
        //        $lastOrderId = static::max('id');
        $newOrderId = $lastOrderId + 1;
        $uniqueId = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $returnNumber = 'return' . $newOrderId . '_' . $uniqueId;

        return $returnNumber;
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function supplier()
    // {
    //     return $this->belongsTo(Supplier::class);
    // }
    // public function transactions()
    // {
    //     return $this->hasMany(PurchaseTransaction::class);
    // }


    public function transactions()
    {
        return $this->hasMany(ReturnTransaction::class,'return_id');
    }
    public function wallets()
    {
        return $this->hasMany(Wallet::class,'return_id');
    }

    public function returnItems()
    {
        return $this->hasMany(ReturnItem::class,'return_id');
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }
    public function customer()
    {
        return $this->hasOne(User::class,'id','customer_id');
    }
    public function stocks()
    {
        return $this->hasMany(Stock::class,'return_id');
    }
}
