<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleInventory extends Model
{
    protected $table = 'vehicle_inventory';

    protected $fillable = [
        'plate_number',
        'brand',
        'car_color',
        'model',
        'year',
    ];

    public static function createVehicle(array $data)
    {
        return self::create($data);
    }

    public static function getAllVehiclesInventory()
    {
        return self::all();
    }

    public static function findVehicle(array $data)
    {
        return self::where($data)->first();
    }

    public static function getVehicleById($id)
    {
        return self::find($id);
    }

    public function updateVehicle(array $data)
    {
        return $this->update($data);
    }

    public function deleteVehicle()
    {
        return $this->delete();
    }
}
