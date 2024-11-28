<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class TripLoadScanner extends Model
{
    use HasFactory;

    protected $table = 'trip_load_scanners';

    protected $guarded = [];

    protected $casts = [
        'extras' => 'array',
        'material_type' => 'array',
    ];

    /**
     * Create a new trip record from API response data.
     *
     * @return TripLoadScanner
     */
    public static function updateOrCreateFromApiData(array $data, array $getUpdatesWithDevices)
    {
        try {
            // Tentukan `trip_type_id` berdasarkan materialName
            $materialName = strtolower($data['materialName']);
            $tripTypeName = in_array($materialName, ['overburden', 'ob', 'over burden']) ? 'dumping' : 'hauling';

            $tripType = TripType::where('name', $tripTypeName)->first();
            $vehicle = Vehicle::where('license_plate', $data['vehicleVrm'] ?? null)->first();
            $device = Device::find($getUpdatesWithDevices['device_id']);

            // Update atau buat TripLoadScanner
            $tripLoadScanner = self::updateOrCreate(
                ['id' => $data['id']],
                [
                    'ticket_no' => $data['ticketNo'],
                    'ls_code' => $data['unitCode'],
                    'vehicle_vrm' => $data['vehicleVrm'],
                    'supplier_name' => $data['supplierName'],
                    'operator_name' => $data['operatorName'],
                    'full_scan_at' => $data['scanFullAt'],
                    'empty_scan_at' => $data['scanEmptyAt'],
                    'volume' => $data['volume'],
                    'sync_at' => $data['scanLoadAt'],
                    'extras' => $data['extra'],
                    'created_at' => $data['createdAt'],
                    'updated_at' => $data['updatedAt'],
                    'material_type' => $data['materialName'],
                ]
            );

            // Update atau buat Trip
            $trip = Trip::updateOrCreate(
                ['trip_load_scanner_id' => $tripLoadScanner->id],
                [
                    'pit_id' => $device->id ?? null,
                    'trip_type_id' => $tripType->id ?? null,
                    'truck_id' => $vehicle->id ?? null,
                    'load_scanner_id' => $device->id,
                    'quantity' => $data['volume'],
                ]
            );

            Log::info("Successfully created or updated trip for device_id {$device->id} and ticket_id {$data['id']}");

            return $trip;
        } catch (\Exception $e) {
            Log::error('Error updating or creating trip: '.$e->getMessage());
            throw $e;
        }
    }
}
