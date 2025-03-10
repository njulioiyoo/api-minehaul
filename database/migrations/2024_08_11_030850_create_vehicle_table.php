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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id()->bigIncrements()->primary();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('pit_id')->constrained()->onDelete('cascade');
            $table->string('display_id', 100)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('vin', 255)->nullable();
            $table->text('tags')->nullable();
            $table->string('license_plate', 50)->nullable();
            $table->foreignId('vehicle_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_make_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_model_id')->constrained()->onDelete('cascade');
            $table->year('year')->nullable();
            $table->foreignId('vehicle_status_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('vehicles');
    }
};
