<?php

declare(strict_types=1);

namespace App\Services;

class RequestHelperService
{
    public function getInputAndId($request, $type, $includeId = false)
    {
        $input = $request->json()->all();
        $dataId = $includeId && isset($input['data']['uid']) ? $input['data']['uid'] : null;
        $input['data']['type'] = $type;

        $queryParams = $request->query();

        return [$input, $dataId, $queryParams];
    }
}
