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
        schema::create('target_types', function (Blueprint $table) {
            $table->id();
            $table->string('target_type');
        });
    }

    /**
     * Reverse the migrations. crear seeders milka
     */
    public function down(): void
    {
        //
        schema::dropIfExists('target_types');
    }
};
