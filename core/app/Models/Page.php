<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    protected $fillable = ['pages_photos', 'product_id'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
