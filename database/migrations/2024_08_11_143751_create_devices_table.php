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
            $table->integer('account_id')->nullable()->default(null);
            $table->integer('pit_id')->nullable()->default(null);
            $table->integer('type_id')->nullable()->default(null);
            $table->string('display_id', 100)->nullable()->default(null);
            $table->string('name', 255)->nullable()->default(null);
            $table->string('sim_id', 255)->nullable()->default(null);
            $table->year('year')->nullable()->default(null);
            $table->integer('make_id')->nullable()->default(null);
            $table->integer('model_id')->nullable()->default(null);
            $table->integer('status_id')->nullable()->default(null);
            $table->enum('status', ['active', 'inactive', 'nullified'])->default('nullified');
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
