<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'id',
        'purchase_number',
        'supplier_id',
        'supplier_note',
        'admin_note',
        'purchase_date',
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
        static::creating(function ($purchase) {
            $purchase->created_by = Auth::id();
            $purchase->purchase_number = static::generatePurchaseNumber($purchase->id);
        });


        // Updating event
        static::updating(function ($purchase) {
            $purchase->updated_by = Auth::id();
        });


        static::deleting(function ($purchase) {

        });

        // Deleting event
        static::deleting(function ($purchase) {
            if ($purchase->isForceDeleting()) {
                // Handle force deleting
                $purchase->purchaseItems()->forceDelete();
                $purchase->stocks()->forceDelete();
                $purchase->transactions()->forceDelete();
            } else {
                // Handle soft deleting
                $purchase->deleted_by = Auth::id();
                $purchase->save();

                $purchase->purchaseItems()->delete();
                $purchase->stocks()->delete();
                $purchase->transactions()->delete();
            }
        });




    }


    protected static function generatePurchaseNumber()
    {
        // Get the last non-soft-deleted order ID from the database
        $lastOrderId = static::withTrashed()->max('id');
//        $lastOrderId = static::max('id');
        $newOrderId = $lastOrderId + 1;
        $uniqueId = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $purchaseNumber = 'PURCH' . $newOrderId . '_' . $uniqueId;

        return $purchaseNumber;
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function transactions()
    {
        return $this->hasMany(PurchaseTransaction::class);
    }



    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }



    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }


}
