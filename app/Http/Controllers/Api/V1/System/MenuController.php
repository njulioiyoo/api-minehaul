<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Services\HeaderService;
use App\Services\RequestHelperService;
use App\Services\System\Menu\MenuService;
use Illuminate\Http\Request;
use LaravelJsonApi\Core\Responses\DataResponse;

class MenuController extends Controller
{
    protected $headerService;

    protected $requestHelperService;

    protected $menuService;

    public function __construct(HeaderService $headerService, RequestHelperService $requestHelperService, MenuService $menuService)
    {
        $this->headerService = $headerService;
        $this->requestHelperService = $requestHelperService;
        $this->menuService = $menuService;
    }

    public function createMenu(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        [$input, $menuId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'menus');

        return $this->menuService->createMenu($input, $headers, $queryParams);
    }

    public function index()
    {
        return new DataResponse((new Menu)->getTree());
    }

    public function updateMenu(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        [$input, $menuId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'menus', true);

        // Panggil metode updateDevice dengan ID yang diperoleh
        return $this->menuService->updateMenu($menuId, $input, $headers, $queryParams);
    }

    public function deleteMenu(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        [$input, $menuId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'menus', true);

        // Panggil metode deleteDevice dengan ID yang diperoleh
        return $this->menuService->deleteMenu($menuId, $input, $headers, $queryParams);
    }
}
