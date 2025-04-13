<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignProduct extends Model
{
    use HasFactory;

    protected $table = 'campaign_products';

    protected $fillable = [
        'campaign_id',
        'product_id',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
