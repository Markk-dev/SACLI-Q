<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueuingDashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Basic return of data
Route::get('/example', function () {
    return response()->json(['message' => 'This is an example API endpoint']);
});

