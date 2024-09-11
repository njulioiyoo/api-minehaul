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
        Schema::create('alert_setups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alert_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('alert_status_id')->constrained()->onDelete('cascade');
            $table->decimal('value_start', 10, 2);
            $table->decimal('value_end', 10, 2);
            $table->foreignId('unit_of_measurement_id')->constrained('unit_of_measurements')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_setups');
    }
};
