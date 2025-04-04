<?php

namespace App\Services;

use App\Models\Device;
use Illuminate\Support\Facades\Log;

class DeviceService
{
    public static function createDevice(array $data): Device
    {
        return Device::create($data);
    }

    public static function createOrGetDevice(array $data): Device
    {
        $existingDevice = Device::where('mac', $data['mac'])->first();

        if ($existingDevice) {
            $existingDevice->update($data);
            Log::info('Device updated', ['mac' => $data['mac']]);
            return $existingDevice;
        }

        Log::info('Device created', ['mac' => $data['mac']]);
        return Device::create($data);
    }

    public static function updateDevice(int $id, array $data): ?Device
    {
        $device = Device::find($id);

        if ($device) {
            $device->update($data);
            Log::info('Device updated by ID', ['id' => $id]);
        } else {
            Log::warning('Device not found to update', ['id' => $id]);
        }

        return $device;
    }
}
