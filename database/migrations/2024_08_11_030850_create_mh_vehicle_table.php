<?php

declare(strict_types=1);

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
        Schema::create('mh_vehicle', function (Blueprint $table) {
            $table->id('vehicle_id');
            $table->string('vehicle_display_id', 100)->nullable();
            $table->string('vehicle_name', 255)->nullable();
            $table->string('vehicle_vin', 255)->nullable();
            $table->string('vehicle_license_plate', 50)->nullable();
            $table->year('vehicle_year')->nullable();
            $table->integer('vehicle_type_id')->nullable();
            $table->integer('vehicle_make_id')->nullable();
            $table->integer('vehicle_model_id')->nullable();
            $table->integer('vehicle_status_id')->nullable();
            $table->enum('dt_status', ['active', 'inactive', 'nullified'])->default('active');
            $table->integer('dt_creator')->nullable();
            $table->timestamp('dt_create_date')->nullable();
            $table->integer('dt_editor')->nullable();
            $table->timestamp('dt_edit_date')->nullable();
            $table->string('uid', 100)->nullable();

            $table->primary('vehicle_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mh_vehicle');
    }
};
