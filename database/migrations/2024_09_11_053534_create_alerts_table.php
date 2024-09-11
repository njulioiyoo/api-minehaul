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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alert_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('alert_status_id')->constrained()->onDelete('cascade');
            $table->decimal('value', 10, 2);
            $table->foreignId('unit_of_measurement_id')->constrained('unit_of_measurements')->onDelete('cascade');
            $table->uuid('device_id');  // UID for device
            $table->uuid('vehicle_id'); // UID for vehicle
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('alert_action_type_id')->constrained('alert_action_types')->onDelete('cascade');
            $table->text('action');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
