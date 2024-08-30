<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    public function up(): void
    {   
        if (Schema::hasTable('locations')) {

            // Schema::table('locations', function (Blueprint $table) {
            //     $table->dropColumn('geom');
            // });

            // $postgisExists = DB::select("SELECT EXISTS (SELECT 1 FROM pg_extension WHERE extname = 'postgis') AS exists");
            // if ($postgisExists[0]->exists) {
            //     DB::statement('DROP EXTENSION postgis');
            // }
            Schema::drop('locations');
        }

        // $postgisExists = DB::select("SELECT EXISTS (SELECT 1 FROM pg_extension WHERE extname = 'postgis') AS exists");
        // if (!$postgisExists[0]->exists) {
        //     DB::statement('CREATE EXTENSION postgis');
        // }

        Schema::create('locations', function (Blueprint $table) {
            $table->id()->bigIncrements()->primary();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('pit_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_type_id')->constrained()->onDelete('cascade');
            
            $table->string('name');
            $table->text('address')->nullable()->default(null);;
            $table->enum('geom_type', ['Polygon', 'Point'])->nullable()->default(null);;
            $table->longText('geom')->nullable()->default(null);;
            $table->float('radius')->nullable()->default(null);;

            $table->integer('created_by')->nullable()->default(null);
            $table->integer('updated_by')->nullable()->default(null);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }
};
