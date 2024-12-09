<?php

declare(strict_types=1);

namespace App\Services\Configuration;

use App\Helpers\PaginationHelper;
use App\Traits\ExceptionHandlerTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EntityCrudService
{
    use ExceptionHandlerTrait;

    /**
     * Generic function to create a new entity and handle cache and response formatting.
     *
     * @param  Model  $model  The model instance (e.g., Device, Driver)
     * @param  array  $inputData  The input data for creating the entity
     * @param  string  $cacheKeyPrefix  The cache key prefix (e.g., 'device' or 'driver')
     * @param  object  $transformer  Transformer to format the data
     * @return array The formatted response after creation
     */
    public function create(
        Model $model,
        array $inputData,
        string $cacheKeyPrefix,
        $transformer
    ) {
        return DB::transaction(function () use ($model, $inputData, $cacheKeyPrefix, $transformer) {
            // Create the new entity
            $entity = $model->create($inputData);

            // Forget cache related to the newly created entity
            Cache::forget("{$cacheKeyPrefix}_{$entity->id}");

            // Return the formatted JSON API response using the transformer
            return $transformer->transform($entity);
        });
    }

    /**
     * Generic function to read data with pagination and filters for any model.
     *
     * @param  Model  $model  The model instance (e.g., Device, Driver)
     * @param  array  $queryParams  The query parameters including filters and pagination info
     * @param  object  $transformer  Transformer to format the data
     * @param  array  $relations  The relationships to eager load (e.g., ['account', 'pit'])
     * @param  array  $defaultFilters  Default filters to apply to the query
     * @return array Paginated data with formatting
     */
    public function read(
        Model $model,
        array $queryParams,
        $transformer,
        array $relations = [],
        array $defaultFilters = []
    ): array {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        // Start building the query with optional relationships
        $query = $model->with($relations);

        // Apply default filters if any
        foreach ($defaultFilters as $field => $value) {
            $query->where($field, $value);
        }

        // Apply filters from query parameters
        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        // Get paginated data
        $items = $query->paginate($perPage, ['*'], 'page[number]', $page);

        // Transform data using the provided transformer
        $data = $items->map(fn ($item) => $transformer->transform($item))->values()->all();

        // Return the formatted paginated data
        return PaginationHelper::format($items, $data);
    }

    /**
     * Update an existing entity and handle cache and response formatting.
     *
     * @param  Model  $model  The model instance (e.g., Device, Driver)
     * @param  string  $uid  The UID of the entity to update
     * @param  array  $inputData  The data to update the entity
     * @param  string  $cacheKeyPrefix  The cache key prefix (e.g., 'device' or 'driver')
     * @param  object  $transformer  The transformer to format the entity
     * @return array The formatted response after update
     */
    public function update(
        Model $model,
        string $uid,
        array $inputData,
        string $cacheKeyPrefix,
        $transformer
    ) {
        return DB::transaction(function () use ($model, $uid, $inputData, $cacheKeyPrefix, $transformer) {
            // Update the entity
            $model->where('uid', $uid)->update($inputData);

            // Retrieve the updated entity
            $entity = $model->where('uid', $uid)->first();

            // If the entity is not found, throw ModelNotFoundException
            if (! $entity) {
                throw new ModelNotFoundException("{$cacheKeyPrefix} not found");
            }

            // Clear the cache and store the updated entity
            Cache::put("{$cacheKeyPrefix}_{$uid}", $entity, 60);

            // Return the formatted JSON API response using the transformer
            return $transformer->transform($entity);
        });
    }

    /**
     * Delete an entity by its UID and handle cache.
     *
     * @param  Model  $model  The model instance (e.g., Device, Driver)
     * @param  string  $uid  The UID of the entity to delete
     * @param  string  $cacheKeyPrefix  The cache key prefix (e.g., 'device' or 'driver')
     * @return array The response after deletion
     */
    public function delete(
        Model $model,
        string $uid,
        string $cacheKeyPrefix
    ) {
        return DB::transaction(function () use ($model, $uid, $cacheKeyPrefix) {
            // Find the entity to delete
            $entity = $model->where('uid', $uid)->first();

            // If the entity is not found, throw ModelNotFoundException
            if (! $entity) {
                throw new ModelNotFoundException("{$cacheKeyPrefix} not found");
            }

            // Delete the entity
            $entity->delete();

            // Forget the cache associated with the entity
            Cache::forget("{$cacheKeyPrefix}_{$uid}");

            // Return a success message after deletion
            return ['message' => ucfirst($cacheKeyPrefix).' deleted successfully'];
        });
    }

    /**
     * Show a single entity based on UID, with cache handling.
     *
     * @param  Model  $model  The model instance (e.g., Device, Driver)
     * @param  string  $uid  The UID of the entity to retrieve
     * @param  string  $cacheKeyPrefix  The cache key prefix (e.g., 'device' or 'driver')
     * @param  object  $transformer  The transformer to format the entity
     * @return array The formatted response after retrieval
     */
    public function show(
        Model $model,
        string $uid,
        string $cacheKeyPrefix,
        $transformer
    ) {
        // Attempt to retrieve the entity from cache first
        $entity = Cache::remember("{$cacheKeyPrefix}_{$uid}", 60, function () use ($model, $uid) {
            return $model->where('uid', $uid)->first();
        });

        // If the entity is not found, throw a ModelNotFoundException
        if (! $entity) {
            throw new ModelNotFoundException("{$cacheKeyPrefix} not found");
        }

        // Return the formatted JSON API response using the transformer
        return $transformer->transform($entity);
    }
}
