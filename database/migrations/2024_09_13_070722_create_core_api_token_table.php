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
        Schema::create('core_api_token', function (Blueprint $table) {
            $table->id()->bigIncrements();
            $table->unsignedBigInteger('user_id');
            $table->string('session_id');
            $table->string('url_call');
            $table->string('url_accessed');
            $table->string('api_token')->unique();
            $table->timestamps();

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
