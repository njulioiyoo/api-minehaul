<?php

declare(strict_types=1);

namespace App\Services;

class RequestHelperService
{
    // Metode untuk mendapatkan input dan ID
    public function getInputAndId($request, $type, $includeId = false)
    {
        $input = $request->json()->all();
        $permissionId = $includeId && isset($input['data']['id']) ? $input['data']['id'] : null;
        $input['data']['type'] = $type;

        $queryParams = $request->query();

        return [$input, $permissionId, $queryParams];
    }
}
