<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Book\BookController;

Route::get('/', function (): JsonResponse {
    $response = new JsonResponse([
        "greeting"      => "Hello, Developer! 👋",
        "message"       => "You’ve reached the root of the SmartLib API. All resources are available under /api endpoints.",
        "note"          => "This is a JSON-only API; Please use proper API endpoints for requests."
    ], 400);

    $data = $response->getData(true);
    $data['status'] = $response->getStatusCode();

    return new JsonResponse($data, $response->getStatusCode());
});

Route::prefix('libraries/books')->group(function () {
    Route::controller(BookController::class)->group(function () {
        Route::get('all', 'index');
        Route::post('publish', 'store');
    });
});