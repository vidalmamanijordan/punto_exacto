<?php

use App\Http\Controllers\Api\CampusController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SearchHistoryController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('campuses', CampusController::class);
    Route::apiResource('places', PlaceController::class);
    Route::apiResource('faqs', FaqController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('ratings', RatingController::class);
    Route::apiResource('favorites', FavoriteController::class);
    Route::apiResource('search-histories', SearchHistoryController::class);
    Route::get('/permissions', [PermissionController::class, 'index']);
});
