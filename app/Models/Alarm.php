<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alarm extends Model
{
    protected $table = 'alarm';
    protected $fillable = ['id_device', 'id_type', 'utc'];
    public $timestamps = false; // Desactivar timestamps si la tabla no tiene `created_at` y `updated_at`

    public function device()
    {
        return $this->belongsTo(Device::class, 'id_device');
    }

    public function alarmType()
    {
        return $this->belongsTo(AlarmType::class, 'id_type');
    }

    public static function createAlarm(array $data)
    {
        return self::create($data);
    }

    public static function updateAlarm($id, array $data)
    {
        $alarm = self::find($id);
        if ($alarm) {
            $alarm->update($data);
        }
        return $alarm;
    }

    public static function getAlarmById($id)
    {
        return self::find($id);
    }

    public static function getAlarmByConditions(array $data)
    {
        return self::where($data)->first();
    }

    public static function findAlarmWithRelations(array $conditions)
    {
        return self::where($conditions)->with(['device', 'alarmType'])->first();
    }

    public static function getAlarmWithRelations($alarm_id)
    {
        return self::where('id', $alarm_id)
            ->with(['device', 'alarmType'])
            ->first();
    }
}
