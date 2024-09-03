<?php

declare(strict_types=1);

namespace App\Services\System\Menu;

use App\Helpers\PaginationHelper;
use App\Models\Menu;
use App\Transformers\MenuTransformer;
use Illuminate\Support\Facades\Log;

class MenuService
{
    public function __construct(public MenuTransformer $transformer) {}

    public function createMenu(array $inputData)
    {
        try {
            $menu = Menu::create($inputData);

            if (! $menu) {
                throw new \Exception('Failed to create menu');
            }

            $transformMenu = $this->transformer->transform($menu);

            return $transformMenu;
        } catch (\Throwable $th) {
            throw $th;
        }
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
        try {
            $menu = Menu::find($menuId);

            if (! $menu) {
                throw new \Exception('Menu not found');
            }

            $menu->update($inputData);

            $transformMenu = $this->transformer->transform($menu);

            return $transformMenu;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteMenu($menuId)
    {
        try {
            $menu = Menu::find($menuId);

            if (! $menu) {
                Log::info('Menu not found with ID: '.$menuId);
                throw new \Exception('Role not found');
            }

            $menu->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
