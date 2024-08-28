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
use Illuminate\Support\Facades\Log;
use LaravelJsonApi\Core\Responses\ErrorResponse;
use LaravelJsonApi\Core\Document\Error;

class MenuController extends Controller
{
    protected $requestHelperService;

    protected $menuService;

    public function __construct(RequestHelperService $requestHelperService, MenuService $menuService)
    {
        $this->requestHelperService = $requestHelperService;
        $this->menuService = $menuService;
    }

    public function createMenu(StoreMenuRequest $request)
    {
        $validatedData = $request->validated();
        $menu = $this->menuService->createMenu($validatedData);

        return new DataResponse($menu);
    }

    public function readMenu()
    {
        return new DataResponse((new Menu)->getTree());
    }

    public function updateMenu(UpdateMenuRequest $request)
    {
        $validatedData = $request->validated();
        [$input, $menuId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'menus', true);
        $menu = $this->menuService->updateMenu($menuId, $validatedData);

        return new DataResponse($menu);
    }

    public function deleteMenu(Request $request)
    {
        [$input, $menuId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'menus', true);

        try {
            $this->menuService->deleteMenu($menuId);
            return response()->json(['message' => 'Menu deleted successfully.']);
        } catch (\Exception $e) {
            Log::error("Error deleting menu: {$e->getMessage()}");
            return new ErrorResponse(collect([
                Error::fromArray([
                    'status' => '500',
                    'title' => 'Internal Server Error',
                    'detail' => $e->getMessage()
                ])
            ]));
        }
    }
}
