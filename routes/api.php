<?php

use App\Http\Controllers\SiteActivityController;
use App\Http\Controllers\SiteAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/site/activities', [SiteActivityController::class, 'index']);

Route::post('/site/login', [SiteAuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/site/logout', [SiteAuthController::class, 'logout']);

Route::middleware(['auth:sanctum', 'site-admin'])->group(function () {
    Route::post('/site/activities', [SiteActivityController::class, 'store']);
    Route::put('/site/activities/{siteActivity}', [SiteActivityController::class, 'update']);
    Route::delete('/site/activities/{siteActivity}', [SiteActivityController::class, 'destroy']);
});