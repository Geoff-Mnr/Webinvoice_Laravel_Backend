<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::apiResources([
    'roles' => \App\Http\Controllers\API\RolesController::class,
    'users' => \App\Http\Controllers\API\UsersController::class,
]);

Route::middleware('auth:sanctum')->group(function () {
    // Other routes that require authentication
});