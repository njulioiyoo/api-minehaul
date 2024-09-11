<?php

declare(strict_types=1);

namespace App\Services\System\Menu;

use App\Helpers\PaginationHelper;
use App\Models\Menu;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\MenuTransformer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuService
{
    use ExceptionHandlerTrait;

    public function __construct(public MenuTransformer $transformer, public Menu $menu) {}

    public function createMenu(array $inputData)
    {
        return DB::transaction(function () use ($inputData) {
            $menu = $this->menu->create($inputData);

            // Clear cache related to users
            Cache::forget('menu_'.$menu->id);

            return $this->formatJsonApiResponse(
                $this->transformer->transform($menu)
            );
        });
    }

    public function readMenu(array $queryParams)
    {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        $query = Menu::query();

        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        $menu = $query->paginate($perPage, ['*'], 'page[number]', $page);

        $data = $menu->map(function ($permission) {
            return $this->transformer->transform($permission);
        })->values()->all(); // Convert to array

        return PaginationHelper::format($menu, $data);
    }

    public function updateMenu(string $menuId, array $inputData)
    {
        return DB::transaction(function () use ($menuId, $inputData) {
            $menu = $this->menu->findOrFail($menuId);

            $menu->update($inputData);

            // Update cache
            Cache::put("menu_$menuId", $menu, 60);

            // Menggunakan transformer untuk format response JSON API
            return $this->formatJsonApiResponse(
                $this->transformer->transform($menu)
            );
        });
    }

    public function deleteMenu($menuId)
    {
        try {
            $menu = $this->menu->findOrFail($menuId);
            $menu->delete();

            // Clear cache
            Cache::forget("menu_$menuId");
        } catch (\Exception $e) {
            Log::error("Error deleting menu with ID: {$menuId}, Error: {$e->getMessage()}");
            throw $e;
        }
    }
}
