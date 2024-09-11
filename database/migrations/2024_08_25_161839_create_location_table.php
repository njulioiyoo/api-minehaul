<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Membuat tabel 'locations' kembali dengan struktur yang benar.
        Schema::create('locations', function (Blueprint $table) {
            // Primary key otomatis
            $table->id();

            // Foreign keys yang terhubung dengan tabel 'accounts', 'pits', dan 'location_types'
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('pit_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_type_id')->constrained()->onDelete('cascade');

            // Kolom nama lokasi
            $table->string('name');

            // Kolom 'geom_type' yang bisa berisi 'Polygon' atau 'Point'
            $table->enum('geom_type', ['Polygon', 'Point'])->nullable();

            // Kolom 'geom' untuk menyimpan data geometris dalam format teks
            $table->longText('geom')->nullable();

            // Kolom radius (jika diperlukan)
            $table->float('radius')->nullable();

            // Kolom untuk tracking siapa yang membuat dan memperbarui record
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            // Kolom timestamp otomatis (created_at dan updated_at)
            $table->timestamps();

            // Kolom untuk soft deletes
            $table->softDeletes();
        });
    }
};
