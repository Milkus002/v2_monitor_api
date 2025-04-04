<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'device';

    protected $fillable = [
        'mac',
        'device_name',
        'sn',
        'id_alarm_type',
    ];

    public static function createDevice(array $data)
    {
        return self::create($data);
    }

    public static function getDeviceById($id)
    {
        return self::find($id);
    }

    public static function getDeviceByConditions(array $conditions)
    {
        return self::where($conditions)->first();
    }

    public static function updateDevice($id, array $data)
    {
        $device = self::find($id);
        if ($device) {
            $device->update($data);
            return $device;
        }
        return null;
    }
}
