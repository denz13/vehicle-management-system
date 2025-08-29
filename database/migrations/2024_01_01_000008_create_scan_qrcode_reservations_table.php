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
        Schema::create('tbl_scan_qrcode_reservation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reserve_vehicle_id')->nullable();
            $table->string('workstate')->nullable();
            $table->string('logtime')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_scan_qrcode_reservation');
    }
};
