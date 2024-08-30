<?php

namespace App\Http\Controllers;

use App\Services\ReferenceModuleService;
use Illuminate\Http\Request;
use App\Traits\ExceptionHandlerTrait;

class ReferenceModuleController extends Controller
{
    use ExceptionHandlerTrait;

    protected $referenceModuleService;

    public function __construct(ReferenceModuleService $referenceModuleService)
    {
        $this->referenceModuleService = $referenceModuleService;
    }

    public function readReference(Request $request)
    {
        $queryParams = $request->query();

        try {
            $response = $this->referenceModuleService->readReference($queryParams);
            return response()->json($response);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error reading permissions');
        }
    }
}
