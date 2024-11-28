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
        Schema::create('scanner_logs', function (Blueprint $table) {
            $table->id();
            $table->text('headers'); // Menyimpan header dalam format JSON
            $table->string('url'); // URL lengkap yang diakses
            $table->string('ip'); // IP klien
            $table->text('body')->nullable(); // Isi body request (opsional)
            $table->string('user_agent')->nullable(); // User agent klien
            $table->string('method')->nullable(); // Metode HTTP
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scanner_logs');
    }
};
