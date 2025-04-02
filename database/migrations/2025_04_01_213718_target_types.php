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
        Schema::create('target_types', function (Blueprint $table) {
            $table->id();
            $table->string('target_type', 25)->nullable(false);
        });
    }

    /**
     * Reverse the migrations. crear seeders milka
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('target_types');
    }
};
