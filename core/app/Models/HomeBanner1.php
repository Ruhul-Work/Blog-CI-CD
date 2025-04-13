<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeBanner1 extends Model
{
    use HasFactory;

    protected $table='home_banner1s';

    protected $fillable = [
        'name',
        'link',
        'status',
        'image',
        'created_by',
        'updated_by',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();

        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
}
