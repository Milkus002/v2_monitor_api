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
        Schema::create('vehicle_inventories', function (Blueprint $table): void {
            $table->id();
            $table->string('plate_number', 50)->unique();
            $table->string('brand', 50);
            $table->string('car_color', 50);
            $table->string('model', 50);
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
        Schema::dropIfExists('vehicle_inventories');
    }
};
