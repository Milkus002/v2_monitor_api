<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Alarm;

class General extends Model
{
    protected $table = 'general';

    protected $fillable = [
        'id_event',
        'id_target',
        'status',
        'image',
    ];

    public static function createGeneral(array $data)
    {
        return self::create($data);
    }

    public static function getGeneralById($id)
    {
        return self::find($id);
    }

    public static function getGeneralByConditions(array $conditions)
    {
        return self::where($conditions)->first();
    }

    public function getGeneralWithRelations($id)
    {
        $generalDetails = $this->find($id);

        if ($generalDetails) {
            $alarmDetails = $generalDetails->alarm;

            return array_merge($generalDetails->toArray(), $alarmDetails ? $alarmDetails->toArray() : []);
        }

        return null;
    }

    public function alarm()
    {
        return $this->belongsTo(Alarm::class, 'id_alarm');
    }
}
