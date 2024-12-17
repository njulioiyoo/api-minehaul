<?php

declare(strict_types=1);

namespace App\Services\System\Menu;

use App\Models\Menu;
use App\Services\Configuration\EntityCrudService;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\MenuTransformer;

class MenuService
{
    use ExceptionHandlerTrait;

    public function __construct(public EntityCrudService $entityCrudService, public MenuTransformer $transformer, public Menu $menu) {}

    public function createMenu(array $inputData)
    {
        // Call the generic create method from EntityCrudService
        return $this->entityCrudService->create(
            $this->menu,            // Model
            $inputData,                    // Input data
            'menu',                       // Cache key prefix
            $this->transformer              // Transformer
        );
    }

    public function readMenu(array $queryParams)
    {
        // Call the generic read method from EntityCrudService
        return $this->entityCrudService->read(
            $this->menu,            // The model instance (Menu)
            $queryParams,                  // The query parameters
            $this->transformer,            // The transformer for Menu
        );
    }

    public function showMenu(string $menuId)
    {
        // Use the generic show method from EntityCrudService for menu details
        return $this->entityCrudService->show(
            $this->menu,    // The model instance (Menu)
            $menuId,            // The menu UID to be fetched
            'menu',              // Cache key prefix for menus
            $this->transformer      // The transformer for Menu
        );
    }

    public function updateMenu(string $menuId, array $inputData)
    {
        return $this->entityCrudService->update(
            $this->menu,
            $menuId,
            $inputData,
            'menu',  // Cache key prefix for menus
            $this->transformer
        );
    }

    public function deleteMenu($menuId)
    {
        return $this->entityCrudService->delete(
            $this->menu,
            $menuId,
            'menu'  // Cache key prefix for menus
        );
    }
}
