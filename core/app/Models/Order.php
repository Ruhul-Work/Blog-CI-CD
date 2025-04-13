<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'id',
        'order_number',
        'order_id',
        'user_id',
        'order_status_id',
        'notes',
        'adjust_amount',
        'discount_amount',
        'coupon_id',
        'coupon_discount',
        'subtotal',
        'tax',
        'shipping_charge',
        'packing_charge',
        'quantity',
        'total',
        'order_type',
        'payment_status',
        'source',
        'packaged_status',
        'sale_date',
        'packaged_at',
        'packaged_by',
        'packager_id',
        'ordered_by',
        'courier_id',
        'completed_at',
        'updated_by',
        'deleted_by',
    ];

    protected static function boot()
    {
        parent::boot();

        // Creating event
        static::creating(function ($order) {
           if (Auth::check()) {
                $order->ordered_by = Auth::id();
            } else {
                // Use the order's user_id if the user is not authenticated
                $order->ordered_by = $order->user_id;
            }
            $order->order_number = static::generateOrderNumber($order->id);
        });


        // Updating event
        static::updating(function ($order) {
            $order->updated_by = Auth::id();
        });


        static::deleting(function ($order) {

        });

        // Deleting event
        static::deleting(function ($order) {
            if ($order->isForceDeleting()) {
                // Handle force deleting
                $order->orderItems()->forceDelete();
                $order->stocks()->forceDelete();
                $order->transactions()->forceDelete();
            } else {
                // Handle soft deleting
                $order->deleted_by = Auth::id();
                $order->save();

                $order->orderItems()->delete();
                $order->stocks()->delete();
                $order->transactions()->delete();
            }
        });




    }


    protected static function generateOrderNumber()
    {
        $lastOrderId = static::withTrashed()->max('id');
        $newOrderId = $lastOrderId + 1;
       $orderNumber = 'EM' . date('dmy') . $newOrderId;

        return $orderNumber;
        
    }
    
    
    
    
    
    


    public function logAction($action)
    {
        // Attributes to exclude from logging
        $excludedAttributes = ['id', 'user_id', 'notes', 'created_at', 'updated_at', 'updated_by', 'deleted_by'];

        // Capture the order attributes before update
        $orderBeforeUpdate = $this->toArray();

        // Log the action along with previous and updated attributes
        $log = new OrderLog();
        $log->order_id = $this->id;
        $log->action = $action;
        $log->user_id = Auth::user()->id;
        $log->previous_attributes = json_encode(Arr::except($orderBeforeUpdate, $excludedAttributes));
        $log->updated_attributes = json_encode(Arr::except($this->toArray(), $excludedAttributes));
        $log->save();
    }













    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function transactions()
    {
        return $this->hasMany(OrderTransaction::class);
    }

    public function shipping()
    {
        return $this->hasOne(OrderShipping::class);
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id', 'id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class);
    }
    
    
       public function paymentMethods()
    {
        return $this->hasManyThrough(PaymentMethod::class, OrderTransaction::class, 'order_id', 'id', 'id', 'method_id');
    }


}
