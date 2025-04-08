<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Alarm;

class Vehicle extends Model
{
    protected $table = 'vehicle';

    protected $fillable = [
        'id_alarm',
        'plate_number',
        'id_car',
        'car_color',
        'image',
        'plate_image',
    ];

    public static function createVehicle(array $data)
    {
        return self::create($data);
    }

    public static function getVehicleById($id)
    {
        return self::find($id);
    }

    public static function getVehicleByConditions(array $data)
    {
        return self::where($data)->first();
    }

    public static function getAllVehiclesByConditions(array $data)
    {
        return self::where($data)->get();
    }

    public function updateVehicle(array $data)
    {
        return $this->update($data);
    }

    public function getVehicleWithRelations($id)
    {
        $vehicle = $this->with('alarm')->find($id);

        if ($vehicle) {
            return $vehicle;
        }

        return null;
    }

    public static function getAllVehiclesWithRelations()
    {
        return self::with('alarm')->get();
    }

    public function alarm()
    {
        return $this->belongsTo(Alarm::class, 'id_alarm');
    }
}
