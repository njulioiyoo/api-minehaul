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
        Schema::create('device_statuses', function (Blueprint $table) {
            $table->id()->bigIncrements()->primary();
            $table->string('name', 255)->nullable()->default(null);
            $table->enum('status_theme', ['primary', 'success', 'info', 'warning', 'danger'])->nullable()->default(null);
            $table->integer('created_by')->nullable()->default(null);
            $table->integer('updated_by')->nullable()->default(null);

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_statuses');
    }
};
