<?php

use App\Http\Controllers\Api\AiController;
use App\Http\Controllers\Api\CampusController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\KnowledgeBaseController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SearchHistoryController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('campuses', CampusController::class);
    Route::apiResource('places', PlaceController::class);
    Route::apiResource('faqs', FaqController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('ratings', RatingController::class);
    Route::apiResource('favorites', FavoriteController::class);
    Route::apiResource('search-histories', SearchHistoryController::class);
    Route::apiResource('knowledge-base', KnowledgeBaseController::class);
    Route::get('knowledge-base-active', [KnowledgeBaseController::class, 'active'])
        ->name('knowledge-base.active');
    Route::patch('knowledge-base/{knowledgeBase}/activate', [KnowledgeBaseController::class, 'activate'])
        ->name('knowledge-base.activate');
    Route::patch('knowledge-base/{knowledgeBase}/deactivate', [KnowledgeBaseController::class, 'deactivate'])
        ->name('knowledge-base.deactivate');
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');;
    Route::post('ai/chat', [AiController::class, 'chat'])->name('ai.chat');
});
