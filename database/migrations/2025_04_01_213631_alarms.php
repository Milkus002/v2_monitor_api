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
        schema::create('alamrs' , function (Blueprint $table) {
            $table->id();
            $table->integer('id_device')
                ->constrained('devices')
                ->onDelete('cascade');
            $table->integer('id_type')
                ->constrained('alarm_types')
                ->onDelete('cascade');
            $table->bigInteger('utc');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        schema::dropIfExists('alamrs');
    }
};
