<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TriggerGroup extends Model
{
    protected $table = 'trigger_groups';

    protected $fillable = [
        'id_trigger_device',
        'id_related_device',
    ];

    public static function createGroup(array $data)
    {
        return self::create($data);
    }

    public static function getGroupById($id)
    {
        return self::find($id);
    }

    public static function getGroupByConditions(array $conditions)
    {
        return self::where($conditions)->first();
    }

    public static function getAllGroupsByConditions(array $conditions)
    {
        return self::where($conditions)->get();
    }

    public static function updateGroup($id, array $data)
    {
        $group = self::find($id);
        if ($group) {
            $group->update($data);
            return $group;
        }
        return null;
    }
}

