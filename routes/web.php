<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $response = new JsonResponse([
        "greeting"      => "Hello, Developer! 👋",
        "message"       => "You’ve reached the root of the SmartLib API. All resources are available under /api endpoints.",
        "note"          => "This is a JSON-only API; Please use proper API endpoints for requests.",
        "tip"           => "Check the API documentation for available routes and parameters."
    ], 400);

    $data = $response->getData(true);
    $data['status'] = $response->getStatusCode();

    return new JsonResponse($data, $response->getStatusCode());
});