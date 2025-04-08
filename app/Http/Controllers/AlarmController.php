<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alarm;
use App\Models\Vehice;
use App\Services\XmlService;
use App\Services\AlarmService;
use App\Services\DeviceService;

class AlarmController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

    public function newDashboard()
    {
        return view('monitor');
    }

    public function saveAlarmData(Request $request)
    {
        $xmlMessage = $request->getContent();

        if ($xmlMessage) {
            file_put_contents(storage_path("logs/alarm_log.xml"), $xmlMessage);

            $json = XmlService::parsingXML($xmlMessage);

            if ($json) {
                $data = XmlService::jsonToArray($json);
                $smartType = AlarmService::findSmartType($data);
                $this->allAlarms($data, $smartType);
            }
        } else {
            file_put_contents(storage_path("logs/alarm_log.xml"), "No se recibió XML.", FILE_APPEND);
        }

        return response()->json(["status" => "success"], 200);
    }

    private function allAlarms($data, $smartType)
    {
        $deviceData = AlarmService::getDeviceInfo($data);
        $device = DeviceService::createOrGetDevice($deviceData);
        $id_device = $device['id'];

        $findType = ['smart_type' => $smartType];
        $type = AlarmService::createOrGetAlarmType($findType);
        $id_type = $type['id'];

        $utc = $data['currentTime']['#text'] ?? '';

        $alarmData = [
            'id_device' => $id_device,
            'id_type' => $id_type,
            'utc' => $utc,
        ];

        $alarm = AlarmService::createAlarm($alarmData);
        $id_alarm = $alarm['id'];

        $this->createSpecificAlarm($data, $smartType, $id_alarm);
    }

    private function createSpecificAlarm($data, $smartType, $id_alarm)
    {
        $methodMap = [
            "AVD" => "processAVD",
            "PEA" => "processPEA",
            "AOIENTRY" => "processAOIENTRY",
            "AOILEAVE" => "processAOILEAVE",
            "PASSLINECOUNT" => "processPasslineCounting",
            "TRAFFIC" => "processTraffic",
            "VFD" => "processVFD",
            "VSD" => "processVSD",
            "VEHICE" => "processVehice"
        ];

        if (isset($methodMap[$smartType])) {
            AlarmService::{$methodMap[$smartType]}($data, $id_alarm);
        }
    }

    public function getAllVehice()
    {
        return response()->json(AlarmService::getAllVehice());
    }

    public function getAllVehiceInfo()
    {
        try {
            $vehices = Vehice::with(['relatedModel1', 'relatedModel2'])->get();

            foreach ($vehices as &$vehice) {
                $oc = AlarmService::getClosestTimeMatch($vehice, "PASSLINECOUNT", "car");
                $vehice["activity"] = $oc["object_state"] ?? "No activity found";
            }

            return response()->json(["status" => "success", "message" => $vehices]);

        } catch (\Exception $e) {
            return response()->json(["status" => "error", "message" => $e->getMessage()], 500);
        }
    }

    public function getAllVehiceInfoByDate(Request $request)
    {
        $date = $request->input('date');

        if ($date) {
            try {
                $vehices = Vehice::whereDate('created_at', $date)->with(['relatedModel1', 'relatedModel2'])->get();

                foreach ($vehices as &$vehice) {
                    $oc = AlarmService::getClosestTimeMatch($vehice, "PASSLINECOUNT", "car");
                    $vehice["activity"] = $oc["object_state"] ?? "No activity found";
                }

                return response()->json(["status" => "success", "message" => $vehices]);

            } catch (\Exception $e) {
                return response()->json(["status" => "error", "message" => $e->getMessage()], 500);
            }
        }

        return response()->json(["status" => "error", "message" => "No date provided"], 400);
    }

    public function oneVehice($id)
    {
        $vehice = Vehice::with(['relatedModel1', 'relatedModel2'])->find($id);

        if ($vehice) {
            return response()->json($vehice);
        }

        return response()->json(["status" => "License not found"], 404);
    }
}
