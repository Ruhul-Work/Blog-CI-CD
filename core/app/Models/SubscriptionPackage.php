<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SubscriptionPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subscription_packages';

    protected $fillable = [
        'title',
        'name',
        'duration',
        'description',
        'mrp_price',
        'discount_type',
        'discount_amount',
        'current_price',
        'status',
        'created_by',
    ];

    protected $casts = [
        'duration' => 'double',
        'mrp_price' => 'double',
        'current_price' => 'double',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function features()
    {
        return $this->hasMany(SubscriptionFeature::class, 'subscription_package_id');
    }
    /**
     * Boot method to handle created_by, updated_by, and deleted_by fields
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically assign user on create/update/delete
        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        static::deleting(function ($model) {
            $model->deleted_by = Auth::id();
            $model->save();
        });
    }
}
