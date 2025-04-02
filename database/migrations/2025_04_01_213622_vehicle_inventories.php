<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        schema::create('vehicle_inventories', function (Blueprint $table): void {
            $table->id();
            $table->string('plate_number');
            $table->string('brand');
            $table->string('car_color');
            $table->string('model');
            $table->integer('year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        schema::dropIfExists('vehicle_inventories');
    }
};
