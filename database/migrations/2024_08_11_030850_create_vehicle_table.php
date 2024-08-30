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
            $table->integer('account_id');
            $table->integer('pit_id');
            $table->string('display_id', 100)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('vin', 255)->nullable();
            $table->string('license_plate', 50)->nullable();
            $table->year('year')->nullable();
            $table->integer('type_id')->nullable();
            $table->integer('make_id')->nullable();
            $table->integer('model_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->enum('status', ['active', 'inactive', 'nullified'])->default('active');
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
        Schema::dropIfExists('vehicle');
    }
};
