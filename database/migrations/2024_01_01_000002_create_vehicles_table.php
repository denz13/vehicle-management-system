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
        Schema::create('tbl_vehicle', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_name');
            $table->string('vehicle_color');
            $table->string('model');
            $table->string('plate_number')->unique();
            $table->integer('capacity');
            $table->date('date_acquired');
            $table->string('vehicle_image')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_vehicle');
    }
};
