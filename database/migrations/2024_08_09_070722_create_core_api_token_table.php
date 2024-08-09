<?php

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
        Schema::create('core_api_token', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Sesuaikan dengan tipe data ID user
            $table->string('session_id'); // Tipe data bisa disesuaikan jika perlu
            $table->string('url_call'); // Tipe data bisa disesuaikan jika perlu
            $table->string('api_token')->unique(); // Unik untuk mencegah token duplikat
            $table->timestamps(); // created_at dan updated_at

            // Tambahkan foreign key constraint jika user_id merujuk ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_api_token');
    }
};
