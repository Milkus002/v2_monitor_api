<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Alarm;

class Vfd extends Model
{
    protected $table = 'vfd';

    protected $fillable = [
        'id_alarm',
        'id_target',
        'sex',
        'age',
        'image',
    ];

    public static function createVfd(array $data)
    {
        return self::create($data);
    }

    public static function getVfdById($id)
    {
        return self::find($id);
    }

    public static function getVfdByConditions(array $data)
    {
        return self::where($data)->first();
    }

    public function getVfdWithRelations()
    {
        $vfdDetails = $this->with('alarm')->first();

        if ($vfdDetails) {
            $alarmDetails = $vfdDetails->alarm; // Relación de Alarm
            $vfdDetails = $vfdDetails->toArray();

            return array_merge($vfdDetails, $alarmDetails->toArray());
        }

        return null;
    }

    public function alarm()
    {
        return $this->belongsTo(Alarm::class, 'id_alarm');
    }
}
