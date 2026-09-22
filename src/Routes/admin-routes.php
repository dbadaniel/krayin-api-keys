<?php

use Illuminate\Support\Facades\Route;
use Webkul\ApiKey\Http\Controllers\ApiKeyController;

Route::middleware(['web', 'admin_locale', 'user'])
    ->prefix(config('app.admin_path'))
    ->group(function () {
        Route::controller(ApiKeyController::class)->prefix('settings/api-keys')->group(function () {
            Route::get('', 'index')->name('admin.settings.api_keys.index');
            Route::post('', 'store')->name('admin.settings.api_keys.store');
            Route::delete('{id}', 'destroy')->name('admin.settings.api_keys.delete');
        });
    });
