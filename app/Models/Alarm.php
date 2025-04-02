<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alarm extends Model
{
    protected $table = 'alarm';
    use HasFactory;
    protected $fillable = [
        'id_device',
        'id_type',
        'utc',
    ];


}
