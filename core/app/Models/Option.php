<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table ="options";

    protected $fillable=[
        'name',
        'type',
        'value',
        'is_locked',
    ];
}
