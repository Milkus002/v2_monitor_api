<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Vsd;

class VsdCar extends Model
{
    protected $table = 'vsd_car';

    protected $fillable = [
        'id_vsd',
        'year',
        'car_type',
        'car_color',
        'brand',
        'model',
    ];

    public static function createVsdCar(array $data)
    {
        return self::create($data);
    }

    public static function getVsdCarById($id)
    {
        return self::find($id);
    }

    public static function getVsdCarByConditions(array $data)
    {
        return self::where($data)->first();
    }

    public function getVsdCarWithRelations()
    {
        $vsdDetails = $this->with('vsd')->first();

        if ($vsdDetails) {
            $vsd = $vsdDetails->vsd; // Relación con VSD

            $vsdDetails = $vsdDetails->toArray();
            $vsdDetails['vsd'] = $vsd->toArray(); // Incluir detalles de VSD en la respuesta

            return $vsdDetails;
        }

        return null;
    }

    public function vsd()
    {
        return $this->belongsTo(Vsd::class, 'id_vsd');
    }
}
