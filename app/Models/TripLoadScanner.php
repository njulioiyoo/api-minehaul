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
     * Create or update a trip record from API response data.
     *
     * @param  array  $data  The API response data for the trip.
     * @param  array  $getUpdatesWithDevices  Device information to update trip.
     * @return TripLoadScanner
     */
    public static function updateOrCreateFromApiData(array $data, array $getUpdatesWithDevices)
    {
        try {
            // Determine the material type and corresponding trip type
            $materialType = self::getMaterialType($data['materialType']);

            // Retrieve related entities
            $vehicle = Vehicle::where('license_plate', $data['vehicleVrm'])->first();
            $tripType = TripType::where('name', $materialType)->first();
            $device = Device::where('id', $getUpdatesWithDevices['device_id'])->first();

            // Update or create the TripLoadScanner record
            self::updateOrCreate(
                ['id' => $data['id']],  // Lookup key (find by ticket_uuid)
                self::getTripLoadScannerData($data, $materialType)
            );

            // Create or update the Trip record
            $trip = Trip::updateOrCreate(
                ['trip_load_scanner_id' => $data['id']],  // Lookup key (find by ticket_id)
                self::getTripData($data, $tripType, $vehicle, $device, $getUpdatesWithDevices)
            );

            Log::info("Successfully created or updated trip for device_id {$device->id} and ticket_id {$data['id']}");

            return $trip;  // Optionally return the created or updated trip object
        } catch (\Exception $e) {
            Log::error('Error updating or creating trip from API data: '.$e->getMessage());
            throw $e;  // Re-throw exception for further handling if needed
        }
    }

    /**
     * Get the material type based on the provided material type string.
     *
     * @param  string  $materialType  The raw material type from API data.
     * @return string The normalized material type.
     */
    private static function getMaterialType(string $materialType): string
    {
        return in_array($materialType, ['overburden', 'ob', 'over burden']) ? 'dumping' : 'hauling';
    }

    /**
     * Get the data array for creating or updating a TripLoadScanner record.
     *
     * @param  array  $data  The API response data for the trip.
     * @param  string  $materialType  The material type of the trip.
     * @return array The data to update or create the TripLoadScanner record.
     */
    private static function getTripLoadScannerData(array $data, string $materialType): array
    {
        return [
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
        ];
    }

    /**
     * Get the data array for creating or updating a Trip record.
     *
     * @param  array  $data  The API response data for the trip.
     * @param  TripType  $tripType  The trip type associated with the trip.
     * @param  Vehicle|null  $vehicle  The vehicle associated with the trip.
     * @param  Device  $device  The device associated with the trip.
     * @param  array  $getUpdatesWithDevices  The device update information.
     * @return array The data to update or create the Trip record.
     */
    private static function getTripData(
        array $data,
        $tripType,
        $vehicle,
        $device,
        array $getUpdatesWithDevices
    ): array {
        return [
            'pit_id' => $device['id'],
            'trip_type_id' => $tripType['id'] ?? null,
            'trip_load_scanner_id' => $data['id'],
            'truck_id' => $vehicle['id'] ?? null,
            'load_scanner_id' => $getUpdatesWithDevices['device_id'],
            'quantity' => $data['volume'],
        ];
    }
}
