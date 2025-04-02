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

        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('mac', 25)->unique();
            $table->string('device_name', 50);
            $table->string('sn', 25);
            $table->foreignId('id_alarm_type')
                ->constrained('alarm_types')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('devices');
    }
};
