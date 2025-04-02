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
        Schema::create('generals', function (Blueprint $table) {
            $table->id();
            $table->integer('id_alarm')
                ->contrained('alarms')
                ->onDelete('cascade');
            $table->integer('id_event');
            $table->integer('id_target');
            $table->string('status');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        schema::dropIfExists('generals');
    }
};
