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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->string('icon')->nullable();
            $table->string('url')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // ID parent menu
            $table->integer('position')->default(0); // Posisi menu
            $table->enum('status', ['active', 'inactive'])->default('active'); // Field status dengan enum

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            // Foreign key constraint untuk parent_id
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
