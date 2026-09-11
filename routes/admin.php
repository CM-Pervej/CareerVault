<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PlatformConnectionController;
use App\Http\Controllers\Admin\PlatformController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web','auth','admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function(){
        Route::get('/',[DashboardController::class,'index'])->name('dashboard');

        Route::get('users/trash',[UserController::class, 'trash'])->name('users.trash');
        Route::patch('users/{user}/restore',[UserController::class, 'restore'])->name('users.restore');
        Route::delete('users/{user}/force-delete',[UserController::class, 'forceDelete'])->name('users.force-delete');
        Route::resource('users',UserController::class);

        Route::get('platforms/trash',[PlatformController::class, 'trash'])->name('platforms.trash');
        Route::patch('platforms/{platform}/restore',[PlatformController::class, 'restore'])->name('platforms.restore');
        Route::delete('platforms/{platform}/force-delete',[PlatformController::class, 'forceDelete'])->name('platforms.force-delete');
        Route::resource('platforms',PlatformController::class);

        Route::resource('platform-connections', PlatformConnectionController::class)
            ->parameters(['platform-connections' => 'platform'])
            ->except(['show', 'destroy']);
});