<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);


Route ::middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'roles' => \App\Http\Controllers\API\RolesController::class,
        'users' => \App\Http\Controllers\API\UsersController::class,
        'customers' => \App\Http\Controllers\API\CustomersController::class,
        'products' => \App\Http\Controllers\API\ProductsController::class,
        'documents' => \App\Http\Controllers\API\DocumentsController::class,
        'documenttypes' => \App\Http\Controllers\API\DocumentTypesController::class,
    ]);
    Route::get('/list-documenttypes', [\App\Http\Controllers\API\DocumentTypesController::class, 'ListDocumentTypes']);
    Route::get('/list-customers', [\App\Http\Controllers\API\CustomersController::class, 'ListCustomers']);
    Route::get('/list-products', [\App\Http\Controllers\API\ProductsController::class, 'ListProducts']);
    Route::get('/profile-user', [\App\Http\Controllers\API\UsersController::class, 'getUserProfile']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
