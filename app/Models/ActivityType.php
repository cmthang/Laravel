<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    protected $table = 'activity_type';
    protected $fillable = [
        'id',
        'name'
    ];
}
