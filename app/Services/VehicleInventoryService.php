<?php

namespace App\Services;

use App\Models\VehicleInventory;

class VehicleInventoryService
{
    public static function findVehicle(array $conditions)
    {
        return VehicleInventory::where($conditions)->first();
    }

    public static function getAllVehiclesInventory()
    {
        $vehicles = VehicleInventory::all();

        if ($vehicles->isEmpty()) {
            return ['status' => 'No vehicles registered found'];
        }

        return $vehicles;
    }

    public static function getVehicleBaseSearchData()
    {
        return self::getAllVehiclesInventory();
    }

    public static function getVehicleBaseDateSelected()
    {
        return self::getAllVehiclesInventory();
    }

    public static function createVehicle(array $data): VehicleInventory
    {
        return VehicleInventory::create($data);
    }

    public static function readVehicle(int $id): ?VehicleInventory
    {
        return VehicleInventory::find($id);
    }

    public static function readAllVehicles()
    {
        return VehicleInventory::all();
    }

    public static function updateVehicle(int $id, array $data): ?VehicleInventory
    {
        $vehicle = VehicleInventory::find($id);

        if ($vehicle) {
            $vehicle->update($data);
        }

        return $vehicle;
    }

    public static function deleteVehicle(int $id): bool
    {
        $vehicle = VehicleInventory::find($id);

        if ($vehicle) {
            return $vehicle->delete();
        }

        return false;
    }
}
