<?php

declare(strict_types=1);

namespace App\Services\System\Menu;

use App\Models\Menu;
use App\Services\HttpService;
use Illuminate\Support\Facades\Log;

class MenuService
{
    protected $httpService;

    public function __construct(HttpService $httpService)
    {
        $this->httpService = $httpService;
    }

    public function createMenu(array $inputData)
    {
        $menu = Menu::create($inputData);

        if (!$menu) {
            throw new \Exception('Failed to create menu');
        }

        return $menu;
    }

    public function updateMenu(string $menuId, array $inputData)
    {
        $menu = Menu::find($menuId);

        if (!$menu) {
            throw new \Exception('Menu not found');
        }

        $menu->update($inputData);

        return $menu;
    }

    public function deleteMenu($menuId)
    {
        $menu = Menu::find($menuId);

        if (!$menu) {
            Log::info('Menu not found with ID: ' . $menuId);
            throw new \Exception('Role not found');
        }

        $menu->delete();
    }
}
