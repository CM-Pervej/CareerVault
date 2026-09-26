<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PlatformConnectionController;
use App\Http\Controllers\Admin\PlatformController;
use App\Http\Controllers\Admin\PlatformGroupController;
use App\Http\Controllers\Admin\PlatformPageController;
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

        Route::get('platform-pages/trash', [PlatformPageController::class, 'trash'])->name('platform-pages.trash');
        Route::patch('platform-pages/{platformPage}/restore', [PlatformPageController::class, 'restore'])->name('platform-pages.restore');
        Route::delete('platform-pages/{platformPage}/force-delete', [PlatformPageController::class, 'forceDelete'])->name('platform-pages.force-delete');
        Route::resource('platform-pages', PlatformPageController::class);

        Route::get('platform-groups/trash', [PlatformGroupController::class, 'trash'])->name('platform-groups.trash');
        Route::patch('platform-groups/{platformGroup}/restore', [PlatformGroupController::class, 'restore'])->name('platform-groups.restore');
        Route::delete('platform-groups/{platformGroup}/force-delete', [PlatformGroupController::class, 'forceDelete'])->name('platform-groups.force-delete');
        Route::resource('platform-groups', PlatformGroupController::class);
});