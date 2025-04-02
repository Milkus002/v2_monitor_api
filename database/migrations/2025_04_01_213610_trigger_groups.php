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
        schema::create('trigger_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_trigger_device')
                ->constrained('devices')
                ->onDelete('cascade');
            $table->foreignId('id_related_device')
                ->constrained('devices')
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
        schema::dropIfExists('trigger_groups');
    }
};
