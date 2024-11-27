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
            // Determine the trip_type_id based on materialType
            $materialType = $data['materialType'] === 'overburden' || $data['materialType'] === 'ob' || $data['materialType'] === 'over burden' ? 'dumping' : 'hauling';
            $vehicleVrm = $data['vehicleVrm'] ?? null;
            $tripType = TripType::where('name', $materialType)->first();
            $vehicle = Vehicle::where('license_plate', $vehicleVrm)->first();
            $device = Device::where('id', $getUpdatesWithDevices['device_id'])->first();

            // Use updateOrCreate to either create a new record or update an existing one in TripLoadScanner
            self::updateOrCreate(
                ['id' => $data['id']],  // Lookup key (find by ticket_uuid)
                [   // Data to update or create
                    'ticket_no' => $data['ticketNo'],
                    'ls_code' => $data['lsCode'],
                    'vehicle_vrm' => $data['vehicleVrm'],
                    'vehicle_size' => $data['vehicleSize'],
                    'supplier_name' => $data['supplierName'],
                    'operator_name' => $data['operatorName'],
                    'full_scan_at' => $data['fullScanAt'],
                    'empty_scan_at' => $data['emptyScanAt'],
                    'volume' => $data['volume'],
                    'sync_at' => $data['syncAt'],
                    'extras' => $data['extras'],
                    'created_at' => $data['createdAt'],
                    'updated_at' => $data['updatedAt'],
                    'profile_id' => $data['profileId'],
                    'user_id' => $data['userId'],
                    'material_type' => $materialType,
                ]
            );

            // Now, create or update the Trip record
            $trip = Trip::updateOrCreate(
                ['trip_load_scanner_id' => $data['id']],  // Lookup key (find by ticket_id)
                [   // Data to update or create
                    'pit_id' => $device['id'],
                    'trip_type_id' => $tripType['id'] ?? null,
                    'trip_load_scanner_id' => $data['id'],
                    'truck_id' => $vehicle['id'] ?? null,
                    'load_scanner_id' => $getUpdatesWithDevices['device_id'],
                    'quantity' => $data['volume'],
                ]
            );

            // Log::info("Successfully created or updated trip for device_id {$device->id} and ticket_id {$data['id']}");

            return $trip;  // Optionally return the created or updated trip object

        } catch (\Exception $e) {
            dd($e);
            Log::error('Error updating or creating trip from API data: '.$e->getMessage());
            throw $e;  // Re-throw exception for further handling if needed
        }
    }
}
