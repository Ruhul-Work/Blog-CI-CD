<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    use HasFactory;

    protected $table ='upazilas';

    protected $fillable = [
        'country_id',
        'district_id',
        'name',
        'own_name',
        'url',
    ];


    public function country(){

        return $this->belongsTo(Country::class, 'country_id', 'id');
    }


    public function city(){

        return $this->belongsTo(City::class, 'district_id', 'id');
    }


}
