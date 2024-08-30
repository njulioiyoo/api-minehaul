<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\DeviceModel;
use App\Models\ReferenceDeviceModel; // Contoh model baru seperti Pits

class ReferenceModuleTransformer
{
    /**
     * Transforms the given models into a grouped array format.
     *
     * @param array $models
     * @return array
     */
    public function transform(array $models): array
    {
        $groupedData = [
            'device_models' => [
                'type' => 'device_models',
                'attributes' => []
            ],
            // 'pits' => [], 
        ];

        foreach ($models as $model) {
            $type = $this->getModelType($model);

            if ($type !== 'unknown') {
                $groupedData[$type]['attributes'][] = $this->transformModel($model, $type);
            }
        }

        return $groupedData;
    }

    /**
     * Determines the type of the model.
     *
     * @param mixed $model
     * @return string
     */
    protected function getModelType($model): string
    {
        if ($model instanceof DeviceModel) {
            return 'device_models';
        }
        //  elseif ($model instanceof ReferenceDeviceModel) {
        //     return 'pits';
        // }

        return 'unknown';
    }

    /**
     * Transforms the model into an array format based on its type.
     *
     * @param mixed $model
     * @param string $type
     * @return array
     */
    protected function transformModel($model, string $type): array
    {
        switch ($type) {
            case 'device_models':
                return $this->transformDeviceModel($model);
                // case 'pits':
                //     return $this->transformReferenceDeviceModel($model);
            default:
                return [];
        }
    }

    /**
     * Transforms DeviceModel into an array format.
     *
     * @param DeviceModel $deviceModel
     * @return array
     */
    protected function transformDeviceModel(DeviceModel $deviceModel): array
    {
        return [
            'id' => $deviceModel->id,
            'device_make' => [
                'id' => $deviceModel->deviceMake->id ?? null,
                'name' => $deviceModel->deviceMake->name ?? null,
                'device_type' => [
                    'id' => $deviceModel->deviceMake->deviceType->id ?? null,
                    'name' => $deviceModel->deviceMake->deviceType->name ?? null,
                ]
            ],
            'name' => $deviceModel->name,
        ];
    }
}
