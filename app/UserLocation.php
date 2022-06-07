<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
    public $timestamps = true;
    protected $table = 'user_locations';
    protected $fillable = [
        'user_id',
        'ip',
        'countryName',
        'countryCode',
        'regionCode',
        'regionName',
        'cityName',
        'areaCode',
        'created_at',
        'updated_at'
    ];
}
