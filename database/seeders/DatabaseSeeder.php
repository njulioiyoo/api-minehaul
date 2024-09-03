<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PassportKeySeeder::class);
        $this->call(PassportClientSeeder::class);

        $this->call(UsersTableSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(MenuSeeder::class);

        $this->call(PersonSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(PitsTableSeeder::class);

        $this->call(DeviceTypeSeeder::class);
        $this->call(DeviceMakeSeeder::class);
        $this->call(DeviceModelSeeder::class);
        $this->call(DeviceImmobilizitationSeeder::class);
        $this->call(DeviceIgnitionSeeder::class);
        $this->call(DeviceStatusSeeder::class);
        $this->call(DevicesTableSeeder::class);

        $this->call(VehicleTypeSeeder::class);
        $this->call(VehicleMakeSeeder::class);
        $this->call(VehicleModelSeeder::class);
        $this->call(VehicleStatusSeeder::class);
        $this->call(VehicleSeeder::class);
    }
}
