<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\General;

class ObjectCounting extends Model
{
    protected $table = 'object_counting';

    protected $fillable = [
        'id_general',
        'object_type',
        'object_state',
        'count',
    ];

    public static function createObjectCounting(array $data)
    {
        return self::create($data);
    }

    public static function getObjectCountingById($id)
    {
        return self::find($id);
    }

    public static function getAllObjectCountings()
    {
        return self::all();
    }

    public static function getObjectCountingByConditions(array $conditions)
    {
        return self::where($conditions)->first();
    }

    public static function getLastObjectCountingByConditions(array $conditions)
    {
        return self::where($conditions)->orderByDesc('created_at')->first();
    }

    public static function getObjectCountingWithRelations($id)
    {
        $objDetails = self::with('general')->find($id);

        if ($objDetails) {
            return $objDetails->toArray();
        }

        return null;
    }

    public function general()
    {
        return $this->belongsTo(General::class, 'id_general');
    }

    public static function findObjectCountingByTime($table, $relations, $conditions, $timestampColumn, $givenTimestamp, $timeMargin)
    {
        return self::findWithRelationsByTime($table, $relations, $conditions, $timestampColumn, $givenTimestamp, $timeMargin);
    }
}
