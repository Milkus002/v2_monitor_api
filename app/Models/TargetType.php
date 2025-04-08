<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetType extends Model
{
    protected $table = 'target_type';

    protected $fillable = [
        'target_type',
    ];

    public static function createTargetType(array $data)
    {
        return self::create($data);
    }

    public static function getTargetTypeById($id)
    {
        return self::find($id);
    }

    public static function getTargetTypeByConditions(array $conditions)
    {
        return self::where($conditions)->first();
    }
}
