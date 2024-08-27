<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationHelper
{
    public static function format(LengthAwarePaginator $paginator, array $data): array
    {
        return [
            'meta' => [
                'page' => [
                    'currentPage' => $paginator->currentPage(),
                    'from' => $paginator->firstItem(),
                    'lastPage' => $paginator->lastPage(),
                    'perPage' => $paginator->perPage(),
                    'to' => $paginator->lastItem(),
                    'total' => $paginator->total(),
                ]
            ],
            'jsonapi' => [
                'version' => '1.0'
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'next' => $paginator->nextPageUrl(),
                'prev' => $paginator->previousPageUrl(),
            ],
            'data' => $data
        ];
    }
}
