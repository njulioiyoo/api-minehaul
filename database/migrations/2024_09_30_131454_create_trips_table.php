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
        Schema::create('trips', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('pit_id')->constrained()->onDelete('cascade');
            $table->string('trip_type_id')->nullable();
            $table->string('trip_load_scanner_id')->nullable();
            $table->string('driver_id')->nullable();
            $table->string('truck_id')->nullable();
            $table->string('excavator_id')->nullable();
            $table->string('load_scanner_id')->nullable();
            $table->decimal('quantity', 12, 4)->nullable();
            $table->timestamp('trip_start_date')->nullable();
            $table->timestamp('trip_end_date')->nullable();
            $table->integer('trip_duration')->nullable();
            $table->string('loading_queue_start_date')->nullable();
            $table->string('loading_queue_end_date')->nullable();
            $table->string('loading_queue_duration')->nullable();
            $table->string('loading_start_date')->nullable();
            $table->string('loading_end_date')->nullable();
            $table->string('loading_duration')->nullable();
            $table->string('dumping_queue_start_date')->nullable();
            $table->string('dumping_queue_end_date')->nullable();
            $table->string('dumping_queue_duration')->nullable();
            $table->string('dumping_start_date')->nullable();
            $table->string('dumping_end_date')->nullable();
            $table->string('dumping_duration')->nullable();
            $table->string('ref_id')->nullable();
            $table->string('last_ref_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
