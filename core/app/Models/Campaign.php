<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Enums\DiscountEnum;
use App\Models\User;
use App\Models\CampaignProduct;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'campaigns';

    protected $fillable = [
        'name',
        'slug',
        'discount',
        'discount_type',
        'icon',
        'meta_title',
        'meta_description',
        'meta_image',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    
    
   

    public function campaign()
    {
        return $this->hasMany(CampaignProduct::class, 'campaign_id','id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    public function productCampaigns(): HasMany
    {
        return $this->hasMany(Campaign::class,'campaign_id','id');
    }


    public function products()
    {
        return $this->belongsToMany(Product::class, 'campaign_products', 'campaign_id', 'product_id');
    }

    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }



    protected $casts = [
        'discount_type' => DiscountEnum::class,
         'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        // Creating event
        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });
        // Updating event
        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
        // Deleting event
        static::deleting(function ($model) {
            $model->deleted_by = Auth::id();
            $model->save(); // Ensure to save the updated deleted_by value
        });
    }
}
