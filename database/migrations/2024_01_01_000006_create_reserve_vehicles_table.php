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
        Schema::create('tbl_reserve_vehicle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('requested_name')->nullable();
            $table->string('destination')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('driver')->nullable();
            $table->unsignedBigInteger('driver_user_id')->nullable();
            $table->datetime('start_datetime')->nullable();
            $table->datetime('end_datetime')->nullable();
            $table->text('reason')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('reservation_type_id')->nullable();
            $table->string('qrcode')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_reserve_vehicle');
    }
};
