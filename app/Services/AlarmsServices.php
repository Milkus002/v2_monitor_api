<?php

namespace App\Services;

use App\Models\Alarm;
use App\Models\Device;
use App\Models\AlarmType;
use App\Models\TargetType;
use App\Models\VehicleInventoryModel;
use App\Models\AVD;
use App\Models\General;
use App\Models\ObjectCounting;
use App\Models\VFD;
use App\Models\Vehice;
use App\Models\VSD;
use App\Models\VsdPerson;
use App\Models\VsdCar;

class AlarmsServices
{
    public function getAllVehice()
    {
        $vehices = Vehice::with(['relatedModel1', 'relatedModel2'])->get();

        if ($vehices->isNotEmpty()) {
            return response()->json($vehices);
        }

        return response()->json(["status" => "No vehicles found"], 404);
    }

    public function createOrGetAlarmType($data)
    {
        return AlarmType::firstOrCreate($data);
    }

    public function createOrGetAlarm($data)
    {
        return Alarm::firstOrCreate($data);
    }

    public static function createOrGetAlarm(array $data)
    {
        return Alarm::firstOrCreate($data);
    }

    public static function createOrGetAlarm(array $data)
    {
        return Alarm::firstOrCreate($data);
    }

    public static function createAlarm(array $data)
    {
        return Alarm::create($data);
    }

    public static function updateAlarm(int $id, array $data)
    {
        $alarm = Alarm::find($id);
        if ($alarm) {
            return $alarm->update($data);
        }

        return false;
    }

    public static function getDeviceInfo(array $data){
        $mac = $data['mac']['#text'] ?? '';
        $deviceName = $data['deviceName']['#text'] ?? '';
        $sn = $data['sn']['#text'] ?? '';
        $smartType = self::findSmartType($data);

        $type = AlarmsServices::createOrGetAlarmType(['smart_type' => $smartType]);
        $id_alarm_type = $type->id;

        return [
            'mac' => $mac,
            'device_name' => $deviceName,
            'id_alarm_type' => $id_alarm_type,
            'sn' => $sn,
        ];
    }

    public static function findSmartType(array $data){
        $smartType = $data['smartType']['#text'] ?? '';
        return $smartType;
    }

    public static function createOrGetObjectCounting(array $data){
        return ObjectCounting::firstOrCreate($data);
    }

    public static function getImageVSD(array $data, $id_event, $id_alarm)
    {
        if (isset($data['vsd']['targetImageData']['targetBase64Data'])) {
            $plateImage = $data['vsd']['targetImageData']['targetBase64Data']['#text'];
            $plateImage = preg_replace('/[\r\n]+/', '', $plateImage);

            if (!empty($plateImage)) {
                $decodedImage = base64_decode($plateImage);

                if ($decodedImage === false) {
                    return null;
                }

                $imagePath = "images/imageDB_vsd_" . preg_replace('/\s+/', '_', $id_event) . "_" . preg_replace('/\s+/', '_', $id_alarm) . ".jpg";

                if (Storage::disk('public')->put($imagePath, $decodedImage)) {
                    return $imagePath;
                }
            }
        }
        return null;
    }

    public static function getImageGeneral(array $data, $id_event, $id_alarm)
    {
        $items = $data['listInfo']['item'] ?? [];

        if (isset($items['targetImageData']['targetBase64Data'])) {
            $plateImage = $items['targetImageData']['targetBase64Data']['#text'];
            $plateImage = preg_replace('/[\r\n]+/', '', $plateImage);

            if ($plateImage) {
                $plateImage = base64_decode($plateImage);
                $imagePath = "images/imageDB_" . preg_replace('/\s+/', '_', $id_event) . "_" . preg_replace('/\s+/', '_', $id_alarm) . ".jpg";

                if (Storage::disk('public')->put($imagePath, $plateImage)) {
                    return $imagePath;
                }
            }
        }
        return null;
    }

    public static function processGeneralAlarm(array $data, $id_alarm, array $items)
    {
        if (count($items) == 0) {
            return null;
        }

        $eventId = $items['eventId']['#text'] ?? null;
        $targetId = $items['targetId']['#text'] ?? null;
        $status = $items['status']['#text'] ?? '';

        // Guardar la imagen general
        $image = self::getImageGeneral($data, $eventId, $id_alarm);

        $result = [
            'id_alarm' => $id_alarm,
            'id_event' => $eventId,
            'id_target' => $targetId,
            'status' => $status,
            'image' => $image,
        ];

        // Crear un registro de General y obtener la relación
        $general = General::create($result);
        $id_general = $general->id;

        // Obtener la información completa con relaciones
        $fullInfo = $general->load('relations'); // Usar load para cargar relaciones

        return $general;
    }

    public static function processObjectType($id_general, $object_type, $object_state, $count)
    {
        if ($count != 0) {
            $objectCountingModel = new ObjectCounting();

            $lastRecord = $objectCountingModel->where('object_type', $object_type)
                ->where('object_state', $object_state)
                ->latest()
                ->first();

            if (!$lastRecord || $count != $lastRecord->count) {
                $objectCounting = $objectCountingModel->create([
                    'id_general'   => $id_general,
                    'object_type'  => $object_type,
                    'object_state' => $object_state,
                    'count'        => $count,
                ]);

                Log::info("Object Counting Id: " . $objectCounting->id);

                // Se asume que el modelo tiene relaciones definidas
                $fullInfo = $objectCounting->load('relations');
                Log::info("Full Info: " . print_r($fullInfo, true));
            }
        }
    }

    public static function processObjectCounting($data, $id_general, $keyword)
    {
        $objectTypes = [
            'enter' => ['car' => 'enterCarCount', 'person' => 'enterPersonCount', 'bike' => 'enterBikeCount'],
            'leave' => ['car' => 'leaveCarCount', 'person' => 'leavePersonCount', 'bike' => 'leaveBikeCount'],
            'exist' => ['car' => 'existCarCount', 'person' => 'existPersonCount', 'bike' => 'existBikeCount']
        ];

        foreach ($objectTypes as $state => $types) {
            foreach ($types as $type => $field) {
                $count = isset($data[$keyword][$field]['#text']) ? $data[$keyword][$field]['#text'] : 0;
                self::processObjectType($id_general, $type, $state, $count);
            }
        }
    }

    public static function processVSD($data, $id_alarm)
    {
        Log::info("Entering processVSD");

        $items = $data['vsd']['vsdInfo']['item'] ?? [];

        $eventId = $items['eventId']['#text'] ?? null;
        $targetId = $items['targetId']['#text'] ?? null;

        $image = self::getImageVSD($data, $eventId, $id_alarm);

        $targetType = $data['vsd']['targetImageData']['targetType']['#text'] ?? null;

        $id_target_type = null;
        if ($targetType) {
            Log::info("Target Type: " . $targetType);
            $findType = ['target_type' => $targetType];
            $type = self::createOrGetTargetType($findType);
            $id_target_type = $type['id'];
        }

        $vsdData = [
            'id_alarm' => $id_alarm,
            'id_event' => $eventId,
            'id_target' => $targetId,
            'id_target_type' => $id_target_type,
            'image' => $image,
        ];

        $vsd = VSD::create($vsdData);

        $id_vsd = $vsd->id;
        if ($targetType == "person") {
            self::processVSDPerson($data, $id_vsd);
        }

        if ($targetType == "car") {
            self::processVSDCar($data, $id_vsd);
        }
    }

    public static function processVSDPerson($data, $id_vsd)
    {
        $personAttr = $data['vsd']['targetImageData']['personAttr'] ?? [];

        $vsdPersonData = [
            'id_vsd' => $id_vsd,
            'upper_length' => $personAttr['upperlength']['#text'] ?? '',
            'upper_color' => $personAttr['uppercolor']['#text'] ?? '',
            'skirt' => $personAttr['skirt']['#text'] ?? '',
            'shoulderbag' => $personAttr['shoulderbag']['#text'] ?? '',
            'sex' => $personAttr['sex']['#text'] ?? '',
            'mask' => $personAttr['mask']['#text'] ?? '',
            'hat' => $personAttr['hat']['#text'] ?? '',
            'glasses' => $personAttr['glasses']['#text'] ?? '',
            'backpack' => $personAttr['backpack']['#text'] ?? '',
            'age' => $personAttr['age']['#text'] ?? '',
        ];

        $vsdPerson = VSDPerson::create($vsdPersonData);

        $id_vsdPerson = $vsdPerson->id;
        $fullInfo = $vsdPerson->load('relations');

        Log::info("VSD Person Info: " . print_r($fullInfo, true));
    }

    public static function processVSDCar($data, $id_vsd){
        $carAttr = $data['vsd']['targetImageData']['carAttr'] ?? [];

        $vsdCarData = [
            'year' => $carAttr['year']['#text'] ?? '',
            'carType' => $carAttr['carType']['#text'] ?? '',
            'color' => $carAttr['color']['#text'] ?? '',
            'brand' => $carAttr['brand']['#text'] ?? '',
            'model' => $carAttr['model']['#text'] ?? '',
        ];

        $vsdCar = VsdCar::create($vsdCarData);

        $id_vsdCar = $vsdCar->id;

        $fullInfo = $vsdCar->load('relations');

        Log::info("VSD Person Info: " . print_r($fullInfo, true));

    }

    public static function processVehicle($data, $id_alarm)
    {
        Storage::put('logs/data_output.txt', print_r($data, true));

        $items = $data['listInfo']['item'] ?? [];
        $items = is_assoc($items) ? [$items] : array_slice($items, -1);

        foreach ($items as $item) {
            $plateNumber = $item['plateNumber']['#text'] ?? '';
            $vehicleId = $item['vehiceId']['#text'] ?? ''; // Verifica si "vehiceId" es correcto
            $colorCar = $item['carAttr']['color']['#text'] ?? '';

            $imagePath = self::saveBase64Image(
                $item['targetImageData']['targetBase64Data']['#text'] ?? null,
                "imageDB_{$plateNumber}_{$id_alarm}.jpg"
            );

            $imageGeneral = self::getImageGeneral($data, $plateNumber, $id_alarm);

            $vehicle = Vehice::create([
                'id_alarm'     => $id_alarm,
                'plate_number' => $plateNumber,
                'id_car'       => $vehicleId,
                'car_color'    => $colorCar,
                'image'        => $imageGeneral,
                'plate_image'  => $imagePath,
            ]);

            $vehicleInfo = VehicleInventory::where('plate_number', $plateNumber)->first();

            $vehicleWithRelations = $vehicle->load('alarm.device');
        }
    }

    private static function saveBase64Image($base64, $filename)
    {
        if (!$base64) return null;

        $imageData = base64_decode(preg_replace('/[\r\n]+/', '', $base64));
        if ($imageData === false) return null;

        $path = public_path("images/{$filename}");
        if (!File::isDirectory(dirname($path))) {
            File::makeDirectory(dirname($path), 0777, true, true);
        }

        file_put_contents($path, $imageData);
        return $path;
    }

    public static function updateVehicleFromObjectCounting($vehicle)
    {
        $createdAt = $vehicle['created_at'];
        $vehicleId = $vehicle['id'];

        return ObjectCounting::findClosestMatch($createdAt, 59);
    }


    public static function getClosestTimeMatch($vehicle, $alarmType, $objectType)
    {
        $vehicleId = $vehicle['id'];
        $vehicleData = Vehice::with('alarm.device.alarmType')->find($vehicleId);

        $deviceTriggerId = $vehicleData->alarm->device->id;

        $targetTimestamp = $vehicle['created_at'];
        $timeMargin = 30;

        return ObjectCounting::whereHas('general.alarm.device.alarmType', function ($query) use ($alarmType, $deviceTriggerId) {
            $query->where('smart_type', $alarmType)
                ->where('device.id', $deviceTriggerId);
        })
            ->where('object_type', $objectType)
            ->whereBetween('created_at', [
                Carbon::parse($targetTimestamp)->subSeconds($timeMargin),
                Carbon::parse($targetTimestamp)->addSeconds($timeMargin)
            ])
            ->first();
    }

    public static function getOldestTimestamp(array $objects = [])
    {
        if (empty($objects)) {
            return null;
        }

        usort($objects, function ($a, $b) {
            return strtotime($a['created_at']) <=> strtotime($b['created_at']);
        });

        return $objects[0];
    }

    public static function processVFD(array $data, int $id_alarm)
    {
        Storage::put('logs/data_output.txt', print_r($data, true));

        $items = $data['listInfo']['item'] ?? [];

        if (empty($items)) {
            Log::info("No items in VFD data.");
            return;
        }

        $targetId = $items['targetId']['#text'] ?? null;
        $age = $items['age']['#text'] ?? null;
        $sex = $items['sex']['#text'] ?? null;

        $image = self::getImageGeneral($data, $targetId, $id_alarm);

        $vfd = Vfd::create([
            'id_alarm'   => $id_alarm,
            'id_target'  => $targetId,
            'sex'        => $sex,
            'age'        => $age,
            'image'      => $image,
        ]);

        $vfd->load('alarm');
    }


    public static function processTraffic(array $data, int $id_alarm)
    {
        $items = $data['traffic']['trafficInfo']['item'] ?? [];
        $keyword = 'traffic';

        $general = self::processGeneralAlarm($data, $id_alarm, $items);
        $id_general = $general['id'];

        self::processObjectCounting($data, $id_general, $keyword);
    }

    public static function processPasslineCounting(array $data, int $id_alarm)
    {
        Storage::put('logs/data_output.txt', print_r($data, true));

        $items = $data['passLineCount']['passLineCountInfo']['item'] ?? [];
        $keyword = 'passLineCount';

        $general = self::processGeneralAlarm($data, $id_alarm, $items);
        $id_general = $general['id'];

        self::processObjectCounting($data, $id_general, $keyword);
    }

    public static function processAOILEAVE(array $data, int $id_alarm)
    {
        Log::info("Entered AOILEAVE processing");

        $items = $data['iveAoiLeave']['aoiInfo']['item'] ?? [];

        $general = self::processGeneralAlarm($data, $id_alarm, $items);

        Log::debug("AOILEAVE general data", $general);
    }


    public static function processAOIENTRY(array $data, int $id_alarm)
    {
        Log::info("Entered AOIENTRY processing");

        $items = $data['iveAoiEntry']['aoiInfo']['item'] ?? [];

        $general = self::processGeneralAlarm($data, $id_alarm, $items);

        Log::debug("AOIENTRY general data", $general);
    }

    public static function processSterile(array $data, int $id_alarm)
    {
        Log::info("Entered processSterile");

        $findType = ['smart_type' => 'PEA2'];
        $type = AlarmService::createOrGetAlarmType($findType);
        $id_type = $type['id'];

        $newAlarmData = ['id_type' => $id_type];
        $alarm = self::updateAlarm($id_alarm, $newAlarmData);

        Log::debug("Updated alarm", $alarm);

        $items = $data['perimeter']['perInfo']['item'] ?? [];

        $sterileData = self::processGeneralAlarm($data, $id_alarm, $items);

        Log::debug("Sterile processed data", $sterileData);
    }

    public static function processPEA(array $data, int $id_alarm)
    {
        Log::info("Entered processPEA");

        $items = $data['tripwire']['tripInfo']['item'] ?? [];

        Log::debug("Tripwire items", $items);

        if (empty($items)) {
            return self::processSterile($data, $id_alarm);
        }

        $peaData = self::processGeneralAlarm($data, $id_alarm, $items);

        Log::debug("PEA processed data", $peaData);
    }

    public static function processAVD(array $data, int $id_alarm)
    {
        Storage::put('logs/data_output.txt', print_r($data, true));

        $items = $data['listInfo']['item'] ?? [];

        if (empty($items)) {
            Log::warning("AVD: No items found.");
            return;
        }

        $eventId = $items['eventId']['#text'] ?? null;
        $alarmType = $items['alarmType']['#text'] ?? '';
        $status = $items['status']['#text'] ?? '';

        $avdData = [
            'id_alarm'    => $id_alarm,
            'id_event'    => $eventId,
            'status'      => $status,
            'alarm_type'  => $alarmType,
        ];

        Log::debug("AVD Data to insert", $avdData);

        $avdModel = new AVD();
        $avd = $avdModel->createAvd($avdData);

        Log::debug("AVD created", $avd);

        $id_avd = $avd['id'];
        $fullInfo = $avdModel->getAvdWithRelations($id_avd);

        Log::debug("AVD full info", $fullInfo);
    }


}
