<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Vsd;

class VsdPerson extends Model
{
    protected $table = 'vsd_person';

    protected $fillable = [
        'id_vsd',
        'upper_length',
        'upper_color',
        'skirt',
        'shoulderbag',
        'sex',
        'mask',
        'hat',
        'glasses',
        'backpack',
        'age',
    ];

    public static function createVsdPerson(array $data)
    {
        return self::create($data);
    }

    public static function getVsdPersonById($id)
    {
        return self::find($id);
    }

    public static function getVsdPersonByConditions(array $data)
    {
        return self::where($data)->first();
    }

    public function getVsdPersonWithRelations()
    {
        $vsdDetails = $this->with('vsd')->first();

        if ($vsdDetails) {
            $vsd = $vsdDetails->vsd;

            $vsdDetails = $vsdDetails->toArray();
            $vsdDetails['vsd'] = $vsd->toArray();

            return $vsdDetails;
        }

        return null;
    }

    public function vsd()
    {
        return $this->belongsTo(Vsd::class, 'id_vsd');
    }
}
