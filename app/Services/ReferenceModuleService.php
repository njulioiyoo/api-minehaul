<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\DeviceModel;
use App\Helpers\PaginationHelper;
use App\Transformers\ReferenceModuleTransformer;

class ReferenceModuleService
{
    protected $transformer;

    public function __construct(ReferenceModuleTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function readReference(array $queryParams)
    {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        $query = DeviceModel::query();

        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        $reference = $query->paginate($perPage, ['*'], 'page[number]', $page);

        // Mendapatkan array dari item-item DeviceModel yang dipaginasi
        $data = $this->transformer->transform($reference->items());

        return PaginationHelper::format($reference, $data);
    }
}
