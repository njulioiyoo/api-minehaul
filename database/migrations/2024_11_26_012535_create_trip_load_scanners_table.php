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
        Schema::create('trip_load_scanners', function (Blueprint $table) {
            $table->text('id')->primary();
            $table->string('ticket_no')->unique();
            $table->string('ls_code');
            $table->string('vehicle_vrm');
            $table->enum('vehicle_size', ['small', 'medium', 'large']); // Pilihan ukuran kendaraan
            $table->string('supplier_name');
            $table->string('operator_name');
            $table->timestamp('full_scan_at')->nullable();
            $table->timestamp('empty_scan_at')->nullable();
            $table->integer('volume');
            $table->timestamp('sync_at')->nullable();
            $table->jsonb('extras')->nullable(); // JSONB untuk performa lebih baik dalam PostgreSQL
            $table->timestamp('created_at')->nullable(); // Kolom createdAt
            $table->timestamp('updated_at')->nullable(); // Kolom updatedAt
            $table->uuid('profile_id'); // UUID untuk profileId
            $table->uuid('user_id'); // UUID untuk userId
            $table->jsonb('material_type')->nullable(); // JSONB untuk materialType
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
