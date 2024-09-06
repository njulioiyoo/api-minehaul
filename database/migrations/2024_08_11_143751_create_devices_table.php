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
        Schema::create('devices', function (Blueprint $table) {
            $table->id()->bigIncrements()->primary();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->integer('pit_id')->nullable()->default(null);
            $table->foreignId('device_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('device_make_id')->constrained()->onDelete('cascade');
            $table->foreignId('device_model_id')->constrained()->onDelete('cascade');
            $table->year('year')->nullable()->default(null);
            $table->string('display_id', 100)->nullable()->default(null);
            $table->string('name', 255)->nullable()->default(null);
            $table->string('sim_id', 255)->nullable()->default(null);
            $table->foreignId('device_immobilizitation_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('device_ignition_type_id')->constrained()->onDelete('cascade');
            $table->uuid('vehicle_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('device_status_id')->constrained()->onDelete('cascade');
            $table->integer('created_by')->nullable()->default(null);
            $table->integer('updated_by')->nullable()->default(null);
            $table->string('uid', 100)->nullable()->default(null);

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
