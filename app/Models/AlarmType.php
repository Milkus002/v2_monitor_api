<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlarmType extends Model
{
    protected $table = 'alarm_type';

    protected $fillable = [
        'smart_type',
        'description',
    ];

    public static function createAlarmType(array $data)
    {
        return self::create($data);
    }

    public static function getAlarmTypeById($id)
    {
        return self::find($id);
    }

    public static function getAlarmTypeByConditions(array $conditions)
    {
        return self::where($conditions)->first();
    }
}
