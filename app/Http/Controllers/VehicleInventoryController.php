<?php

namespace App\Http\Controllers;

use App\Models\VehicleInventory;
use Illuminate\Http\Request;

class VehicleInventoryController extends Controller
{
    //SI CADA Usuario TIENE MUCHOS VehicleInventory ENTONCES QUEDARIA ASI
    //tambien tendria que agregar foreinId USER EN Vehicle inventory
    /**
     * use Illuminate\Routing\Controllers\HasMiddleware
     * use Illuminate\Routing\Controllers\Middleware
     * class VehicleInventoryController extends Controller implements HasMiddleware{
     *      public static function middleware(){
     *          return[
     *              new Middleware('auth:sanctum',except:['index','show'])
     *          ];
     *      }
     * }
     *
     *
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //SI CADA Usuario TIENE MUCHOS VehicleInventory ENTONCES QUEDARIA ASI
        /*
        $fields = $request->validate([
            'title'=>'required|max:255'
        ]);
        $vehicleInventory = $request->user()->vehicleInventory()->create($fields);
        return $vehicleInventory;
        */
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleInventory $vehicleInventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleInventory $vehicleInventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleInventory $vehicleInventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleInventory $vehicleInventory)
    {
        //
    }
}
