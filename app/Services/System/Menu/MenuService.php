<?php

declare(strict_types=1);

namespace App\Services\System\Menu;

use App\Services\HttpService;

class MenuService
{
    protected $httpService;

    public function __construct(HttpService $httpService)
    {
        $this->httpService = $httpService;
    }

    public function createMenu($inputData, $headers, $queryParams)
    {
        $data = [
            'headers' => $headers,
            'json' => $inputData,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('post', route('v1.menus.store'), $data);
    }

    public function readMenu($queryParams, $headers)
    {
        $data = [
            'headers' => $headers,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('get', route('v1.menus.index'), $data);
    }

    public function updateMenu($menuId, $inputData, $headers, $queryParams)
    {
        $data = [
            'headers' => $headers,
            'json' => $inputData,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('patch', route('v1.menus.update', ['menu' => $menuId]), $data);
    }

    public function deleteMenu($menuId, $inputData, $headers, $queryParams)
    {
        $data = [
            'headers' => $headers,
            'json' => $inputData,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('delete', route('v1.menus.destroy', ['menu' => $menuId]), $data);
    }
}
