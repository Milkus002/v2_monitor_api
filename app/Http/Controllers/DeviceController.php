<?php


namespace App\Http\Controllers;

use App\Models\Device;
use App\Services\DeviceServices;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function createDeviceAction(Request $request)
    {
        try {
            DeviceServices::createDevice($request->all());

            return response()->json(['message' => 'Device created successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
