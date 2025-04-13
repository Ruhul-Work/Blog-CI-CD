<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OrderStatus extends Model
{
    use HasFactory;

    protected $table = 'order_statuses';


    protected $fillable = [
        'name',
        'created_by',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });
    }


    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
