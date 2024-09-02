<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vehicle_models')->truncate();

        $vehicleMakes = DB::table('vehicle_makes')->pluck('id', 'name');

        $vehicleModels = [
            ['name' => 'CAT 770', 'make_name' => 'Caterpillar'],
            ['name' => 'CAT 772', 'make_name' => 'Caterpillar'],
            ['name' => 'CAT 773', 'make_name' => 'Caterpillar'],
            ['name' => 'CAT 775', 'make_name' => 'Caterpillar'],
            ['name' => 'CAT 777', 'make_name' => 'Caterpillar'],
            ['name' => 'CAT 785', 'make_name' => 'Caterpillar'],
            ['name' => 'CAT 789', 'make_name' => 'Caterpillar'],
            ['name' => 'Komatsu HD465', 'make_name' => 'Komatsu'],
            ['name' => 'Komatsu HD605', 'make_name' => 'Komatsu'],
            ['name' => 'Komatsu HD785', 'make_name' => 'Komatsu'],
            ['name' => 'Komatsu HM300', 'make_name' => 'Komatsu'],
            ['name' => 'Komatsu HM400', 'make_name' => 'Komatsu'],
            ['name' => 'Hitachi EH1100-5', 'make_name' => 'Hitachi'],
            ['name' => 'Hitachi EH3500AC-3', 'make_name' => 'Hitachi'],
            ['name' => 'Hitachi EH4000AC-3', 'make_name' => 'Hitachi'],
            ['name' => 'Hitachi EH5000AC-3', 'make_name' => 'Hitachi'],
            ['name' => 'Volvo FMX', 'make_name' => 'Volvo'],
            ['name' => 'Volvo A25G', 'make_name' => 'Volvo'],
            ['name' => 'Volvo A30G', 'make_name' => 'Volvo'],
            ['name' => 'Volvo A40G', 'make_name' => 'Volvo'],
            ['name' => 'Volvo VHD series', 'make_name' => 'Volvo'],
            ['name' => 'CAT 301.5', 'make_name' => 'Caterpillar'],
            ['name' => 'CAT 302.7D CR', 'make_name' => 'Caterpillar'],
            ['name' => 'CAT 305.5E2', 'make_name' => 'Caterpillar'],
            ['name' => 'CAT 320', 'make_name' => 'Caterpillar'],
            ['name' => 'CAT 336', 'make_name' => 'Caterpillar'],
            ['name' => 'CAT 349', 'make_name' => 'Caterpillar'],
            ['name' => 'CAT 395', 'make_name' => 'Caterpillar'],
            ['name' => 'Komatsu PC30', 'make_name' => 'Komatsu'],
            ['name' => 'Komatsu PC55MR-5', 'make_name' => 'Komatsu'],
            ['name' => 'Komatsu PC138USLC-11', 'make_name' => 'Komatsu'],
            ['name' => 'Komatsu PC200-10', 'make_name' => 'Komatsu'],
            ['name' => 'Komatsu PC210LC-11', 'make_name' => 'Komatsu'],
            ['name' => 'Komatsu PC300-8', 'make_name' => 'Komatsu'],
            ['name' => 'Komatsu PC490LC-11', 'make_name' => 'Komatsu'],
            ['name' => 'Hitachi ZX26U-5', 'make_name' => 'Hitachi'],
            ['name' => 'Hitachi ZX55U-5', 'make_name' => 'Hitachi'],
            ['name' => 'Hitachi ZX130-6', 'make_name' => 'Hitachi'],
            ['name' => 'Hitachi ZX200-6', 'make_name' => 'Hitachi'],
            ['name' => 'Hitachi ZX350LC-6', 'make_name' => 'Hitachi'],
            ['name' => 'Hitachi EX1200-7', 'make_name' => 'Hitachi'],
            ['name' => 'Hitachi EX8000-6', 'make_name' => 'Hitachi'],
            ['name' => 'Volvo EC20D', 'make_name' => 'Volvo'],
            ['name' => 'Volvo EC140E', 'make_name' => 'Volvo'],
            ['name' => 'Volvo EC220E', 'make_name' => 'Volvo'],
            ['name' => 'Volvo EC300E', 'make_name' => 'Volvo'],
            ['name' => 'Volvo EC480E', 'make_name' => 'Volvo'],
            ['name' => 'Volvo EC750E', 'make_name' => 'Volvo'],
            ['name' => 'Volvo EC950F', 'make_name' => 'Volvo'],
            ['name' => 'Kubota KX018-4', 'make_name' => 'Kubota'],
            ['name' => 'Kubota KX040-4', 'make_name' => 'Kubota'],
            ['name' => 'Kubota U55-4', 'make_name' => 'Kubota'],
            ['name' => 'Kubota KX080-4', 'make_name' => 'Kubota'],
            ['name' => 'Kubota KX121-3', 'make_name' => 'Kubota'],
        ];

        foreach ($vehicleModels as $vehicleModel) {
            $makeName = $vehicleModel['make_name'];

            if (isset($vehicleMakes[$makeName])) {
                DB::table('vehicle_models')->insert([
                    'name' => $vehicleModel['name'],
                    'vehicle_make_id' => $vehicleMakes[$makeName],
                    'created_by' => null,
                    'updated_by' => null,
                    'created_at' => null,
                    'updated_at' => null,
                    'deleted_at' => null,
                ]);
            }
        }
    }
}
