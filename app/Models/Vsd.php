<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Alarm;
use App\Models\TargetType;

class Vsd extends Model
{
    protected $table = 'vsd';

    protected $fillable = [
        'id_alarm',
        'id_event',
        'id_target',
        'id_target_type',
        'image',
    ];

    public static function createVsd(array $data)
    {
        return self::create($data);
    }

    public static function getVsdById($id)
    {
        return self::find($id);
    }

    public static function getVsdByConditions(array $data)
    {
        return self::where($data)->first();
    }

    public function getVsdWithRelations()
    {
        $vsdDetails = $this->with(['alarm', 'targetType'])->first();

        if ($vsdDetails) {
            $alarmDetails = $vsdDetails->alarm; // Relación de Alarm
            $targetTypeDetails = $vsdDetails->targetType; // Relación de TargetType

            $vsdDetails = $vsdDetails->toArray();

            return array_merge($vsdDetails, $alarmDetails->toArray(), $targetTypeDetails->toArray());
        }

        return null;
    }

    public function alarm()
    {
        return $this->belongsTo(Alarm::class, 'id_alarm');
    }

    public function targetType()
    {
        return $this->belongsTo(TargetType::class, 'id_target_type');
    }
}
