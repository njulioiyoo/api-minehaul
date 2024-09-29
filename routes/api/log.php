<?php

declare(strict_types=1);

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/log', function (Request $request) {
    $log = new Log;

    $log->method = $request->method();
    $log->url = $request->fullUrl();
    $log->request_headers = json_encode($request->headers->all());
    $log->request_body = json_encode($request->all());

    $log->save();

    $responseData = [
        'status' => 'success',
        'message' => 'Request logged successfully',
    ];

    $log->response = json_encode($responseData);
    $log->save();

    return response()->json($responseData);
});
