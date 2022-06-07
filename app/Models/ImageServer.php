<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageServer extends Model
{
    protected $table = 'image_server';
    protected $fillable = [
        'id',
        'zone',
        'fs_id',
        'available'
    ];
}
