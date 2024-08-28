<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\System\Menu\StoreMenuRequest;
use App\Http\Requests\System\Menu\UpdateMenuRequest;
use App\Models\Menu;
use App\Services\RequestHelperService;
use App\Services\System\Menu\MenuService;
use Illuminate\Http\Request;
use LaravelJsonApi\Core\Responses\DataResponse;
use App\Traits\ExceptionHandlerTrait;

class MenuController extends Controller
{
    use ExceptionHandlerTrait;

    protected $requestHelperService;
    protected $menuService;

    public function __construct(RequestHelperService $requestHelperService, MenuService $menuService)
    {
        $this->requestHelperService = $requestHelperService;
        $this->menuService = $menuService;
    }

    public function createMenu(StoreMenuRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $menu = $this->menuService->createMenu($validatedData);

            return new DataResponse($menu);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error creating menu');
        }
    }

    public function readMenu()
    {
        try {
            return new DataResponse((new Menu)->getTree());
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error reading menu');
        }
    }

    public function updateMenu(UpdateMenuRequest $request)
    {
        try {
            $validatedData = $request->validated();
            [$input, $menuId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'menus', true);
            $menu = $this->menuService->updateMenu($menuId, $validatedData);

            return new DataResponse($menu);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error updating menu');
        }
    }

    public function deleteMenu(Request $request)
    {
        [$input, $menuId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'menus', true);

        try {
            $this->menuService->deleteMenu($menuId);
            return response()->json(['message' => 'Menu deleted successfully.']);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error deleting menu');
        }
    }
}
