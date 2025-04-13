<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class City extends Model
{
    use HasFactory;

    protected $table ='cities';

    protected $fillable = [
        'country_id',
        'name',
        'own_name',
        'lat',
        'lon',
        'url',
    ];

    public function country(){

        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}
