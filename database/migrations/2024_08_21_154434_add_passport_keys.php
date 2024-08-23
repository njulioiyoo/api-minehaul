<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Artisan::call('passport:keys');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
