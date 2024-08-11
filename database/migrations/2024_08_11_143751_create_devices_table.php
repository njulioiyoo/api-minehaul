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
            $table->increments('device_id');
            $table->integer('account_id')->nullable()->default(null);
            $table->integer('device_type_id')->nullable()->default(null);
            $table->string('device_display_id', 100)->nullable()->default(null);
            $table->string('device_name', 255)->nullable()->default(null);
            $table->string('device_sim_id', 255)->nullable()->default(null);
            $table->year('device_year')->nullable()->default(null);
            $table->integer('device_make_id')->nullable()->default(null);
            $table->integer('device_model_id')->nullable()->default(null);
            $table->integer('device_status_id')->nullable()->default(null);
            $table->enum('dt_status', ['active', 'inactive', 'nullified'])->default('active');
            $table->integer('dt_creator')->nullable()->default(null);
            $table->dateTime('dt_create_date')->nullable()->default(null);
            $table->integer('dt_editor')->nullable()->default(null);
            $table->dateTime('dt_edit_date')->nullable()->default(null);
            $table->string('uid', 100)->nullable()->default(null);

            $table->primary('device_id');
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
