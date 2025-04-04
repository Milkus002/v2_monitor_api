<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Alarm;

class Avd extends Model
{
    protected $table = 'avd';

    protected $fillable = [
        'id_alarm',
        'id_event',
        'status',
        'alarm_type',
    ];

    public function alarm()
    {
        return $this->belongsTo(Alarm::class, 'id_alarm');
    }

    public static function createAvd(array $data)
    {
        return self::create($data);
    }

    public static function getAvdById($id)
    {
        return self::find($id);
    }

    public static function getAvdByConditions(array $conditions)
    {
        return self::where($conditions)->first();
    }

    public static function getAvdWithRelations($id)
    {
        $avd = self::with('alarm')->find($id);

        return $avd ? $avd->toArray() : null;
    }
}
