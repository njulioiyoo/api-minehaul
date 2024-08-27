<?php

declare(strict_types=1);

namespace App\Services\Configuration\Device;

use App\Models\Device;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use LaravelJsonApi\Core\Document\Error;
use LaravelJsonApi\Core\Responses\ErrorResponse;

class DeviceService
{
    public function createDevice(array $inputData)
    {
        // Define validation rules
        $rules = [
            'name' => ['required', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
            'pit_id' => ['nullable', 'integer', 'regex:/^\d+$/'],
            'device_type_id' => ['required', 'integer', 'regex:/^\d+$/'],
            'device_make_id' => ['required', 'integer', 'regex:/^\d+$/'],
            'device_model_id' => ['nullable', 'integer', 'regex:/^\d+$/'],
            'display_id' => ['required', 'string', 'regex:/^[A-Za-z0-9_-]+$/'],
            'sim_id' => ['nullable', 'string', 'regex:/^[0-9]{10,20}$/'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ];

        // Validate the input data
        $validator = Validator::make($inputData, $rules);

        if ($validator->fails()) {
            // Throw a validation exception with JSON:API errors
            throw new ValidationException($validator, $this->formatValidationErrors($validator));
        }

        // Get validated data
        $validatedData = $validator->validated();

        // Create the device
        $device = Device::create($validatedData);

        if (!$device) {
            throw new \Exception('Failed to create device');
        }

        return $device;
    }

    public function readDevice(array $queryParams, array $headers)
    {
        // Define default pagination parameters
        $perPage = $queryParams['page']['size'] ?? 15;
        $page = $queryParams['page']['number'] ?? 1;

        // Fetch devices with relations
        $query = Device::with(['account', 'pit', 'deviceType', 'deviceMake', 'deviceModel']);

        // Apply filters if needed
        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        // Apply pagination
        $devices = $query->paginate($perPage, ['*'], 'page[number]', $page);

        // Transform data to include relations
        $data = $devices->map(function ($device) {
            return [
                'type' => 'devices',
                'id' => $device->uid, // Assuming 'uid' is used as the ID in the response
                'attributes' => [
                    'id' => $device->id,
                    'account' => $device->account ? [
                        'id' => $device->account->id,
                        'company_code' => $device->account->company_code,
                        'company_name' => $device->account->company_name,
                    ] : null,
                    'pit' => $device->pit ? [
                        'id' => $device->pit->id,
                        'name' => $device->pit->name,
                        'description' => $device->pit->description,
                    ] : null,
                    'device_type' => $device->deviceType ? [
                        'id' => $device->deviceType->id,
                        'name' => $device->deviceType->name,
                    ] : null,
                    'device_make' => $device->deviceMake ? [
                        'id' => $device->deviceMake->id,
                        'name' => $device->deviceMake->name,
                    ] : null,
                    'device_model' => $device->deviceModel ? [
                        'id' => $device->deviceModel->id,
                        'name' => $device->deviceModel->name,
                    ] : null,
                    'display_id' => $device->display_id,
                    'name' => $device->name,
                    'sim_id' => $device->sim_id,
                    'year' => $device->year,
                    'status' => $device->status,
                    'uid' => $device->uid,
                ],
                'links' => [
                    'self' => url("/api/v1/devices/{$device->uid}"),
                ],
            ];
        });

        return [
            'meta' => [
                'page' => [
                    'currentPage' => $devices->currentPage(),
                    'from' => $devices->firstItem(),
                    'lastPage' => $devices->lastPage(),
                    'perPage' => $devices->perPage(),
                    'to' => $devices->lastItem(),
                    'total' => $devices->total(),
                ]
            ],
            'jsonapi' => [
                'version' => '1.0'
            ],
            'links' => [
                'first' => $devices->url(1),
                'last' => $devices->url($devices->lastPage()),
                'next' => $devices->nextPageUrl(),
                'prev' => $devices->previousPageUrl(),
            ],
            'data' => $data->values()->all() // Convert to array
        ];
    }

    public function updateDevice(string $deviceUid, array $inputData)
    {
        $device = Device::find($deviceUid);

        if (!$device) {
            throw new \Exception('Device not found');
        }

        // Define validation rules
        $rules = [
            'name' => ['sometimes', 'string', 'max:255'],
            'account_id' => ['sometimes', 'string', 'regex:/^\d+$/'],
            'pit_id' => ['nullable', 'string', 'regex:/^\d+$/'],
            'device_type_id' => ['sometimes', 'string', 'regex:/^\d+$/'],
            'device_make_id' => ['sometimes', 'string', 'regex:/^\d+$/'],
            'device_model_id' => ['nullable', 'string', 'regex:/^\d+$/'],
            'display_id' => ['sometimes', 'string', 'regex:/^[a-zA-Z0-9-]+$/'],
            'sim_id' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9-]+$/'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
            'uid' => ['sometimes', 'string', 'unique:devices,uid,' . $device->id],
        ];

        // Validate the input data
        $validator = Validator::make($inputData, $rules);

        if ($validator->fails()) {
            // Throw a validation exception with JSON:API errors
            throw new ValidationException($validator, $this->formatValidationErrors($validator));
        }

        // Get validated data
        $validatedData = $validator->validated();

        // Update the device
        $device->update($validatedData);

        return $device;
    }

    public function deleteDevice(string $deviceUid, array $inputData, array $headers, array $queryParams)
    {
        $device = Device::find($deviceUid);

        if (!$device) {
            throw new \Exception('Device not found');
        }

        $device->delete();
    }

    /**
     * Format validation errors to JSON:API Error objects.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return ErrorResponse
     */
    private function formatValidationErrors($validator): ErrorResponse
    {
        $errors = collect($validator->errors()->toArray())->map(function ($messages, $field) {
            return Error::fromArray([
                'status' => '422',
                'title' => 'Validation Error',
                'detail' => implode(', ', $messages),
                'source' => ['pointer' => "/data/attributes/{$field}"]
            ]);
        });

        return new ErrorResponse($errors);
    }
}
