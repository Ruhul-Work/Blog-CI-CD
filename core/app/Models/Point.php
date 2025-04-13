<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $table = 'points';
    protected $fillable = [
        'user_id',
        'blog_id',
        'debit',
        'credit',
        'balance',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}