<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->middleware('permission:dashboard.view')->name('dashboard');

    Route::get('/categories', function () {
        return inertia('Categories/Index');
    })->middleware('permission:category.view')->name('categories.index');

    Route::get('/campus', function () {
        return inertia('Campus/Index');
    })->middleware('permission:campus.view')->name('campuses.index');

    Route::get('/places', function () {
        return inertia('Places/Index');
    })->middleware('permission:place.view')->name('places.index');

    Route::get('/faqs', function () {
        return inertia('Faqs/Index');
    })->middleware('permission:faq.view')->name('faqs.index');

    Route::get('/roles', function () {
        return inertia('Roles/Index');
    })->middleware('permission:role.view')->name('roles.index');

    Route::get('/users', function () {
        return inertia('Users/Index');
    })->middleware('permission:user.view')->name('users.index');

    Route::get('/ratings', function () {
        return inertia('Ratings/Index');
    })->middleware('permission:ratings.view')->name('ratings.index');
});

require __DIR__ . '/settings.php';
