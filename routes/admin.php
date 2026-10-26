<?php

use App\Http\Controllers\Admin\BusinessModelController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CompanySizeController;
use App\Http\Controllers\Admin\CompanyTypeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PlatformConnectionController;
use App\Http\Controllers\Admin\PlatformController;
use App\Http\Controllers\Admin\PlatformGroupController;
use App\Http\Controllers\Admin\PlatformPageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\IndustryController;
use App\Http\Controllers\Admin\OwnershipTypeController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\TechnologyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web','auth','admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function(){
        Route::get('/',[DashboardController::class,'index'])->name('dashboard');

        // User Module
        Route::get('users/trash',[UserController::class, 'trash'])->name('users.trash');
        Route::patch('users/{user}/restore',[UserController::class, 'restore'])->name('users.restore');
        Route::delete('users/{user}/force-delete',[UserController::class, 'forceDelete'])->name('users.force-delete');
        Route::resource('users',UserController::class);

        // Platform Section ---------------------------------------------------------------------------------------------------------
        // Platform Module
        Route::get('platforms/trash',[PlatformController::class, 'trash'])->name('platforms.trash');
        Route::patch('platforms/{platform}/restore',[PlatformController::class, 'restore'])->name('platforms.restore');
        Route::delete('platforms/{platform}/force-delete',[PlatformController::class, 'forceDelete'])->name('platforms.force-delete');
        Route::resource('platforms',PlatformController::class);

        // Platform Connection Module
        Route::resource('platform-connections', PlatformConnectionController::class)
            ->parameters(['platform-connections' => 'platform'])
            ->except(['show', 'destroy']);

        // Platform Page Module
        Route::get('platform-pages/trash', [PlatformPageController::class, 'trash'])->name('platform-pages.trash');
        Route::patch('platform-pages/{platformPage}/restore', [PlatformPageController::class, 'restore'])->name('platform-pages.restore');
        Route::delete('platform-pages/{platformPage}/force-delete', [PlatformPageController::class, 'forceDelete'])->name('platform-pages.force-delete');
        Route::resource('platform-pages', PlatformPageController::class);

        // Platform Group Module
        Route::get('platform-groups/trash', [PlatformGroupController::class, 'trash'])->name('platform-groups.trash');
        Route::patch('platform-groups/{platformGroup}/restore', [PlatformGroupController::class, 'restore'])->name('platform-groups.restore');
        Route::delete('platform-groups/{platformGroup}/force-delete', [PlatformGroupController::class, 'forceDelete'])->name('platform-groups.force-delete');
        Route::resource('platform-groups', PlatformGroupController::class);

        // Reference Section ---------------------------------------------------------------------------------------------------------
        // Industry Module
        Route::get('industries-trash', [IndustryController::class, 'trash'])->name('industries.trash');
        Route::patch('industries/{industry}/restore', [IndustryController::class, 'restore'])->withTrashed()->name('industries.restore');
        Route::delete('industries/{industry}/force-delete', [IndustryController::class, 'forceDelete'])->withTrashed()->name('industries.force-delete');
        Route::resource('industries', IndustryController::class)->withTrashed();
    
        // Country Module
        Route::get('countries-trash',[CountryController::class,'trash'])->name('countries.trash');
        Route::patch('countries/{country}/restore',[CountryController::class,'restore'])->withTrashed()->name('countries.restore');
        Route::delete('countries/{country}/force-delete',[CountryController::class,'forceDelete'])->withTrashed()->name('countries.force-delete');
        Route::resource('countries', CountryController::class)->withTrashed();

        // State Module
        Route::get('states-trash', [StateController::class, 'trash'])->name('states.trash');
        Route::patch('states/{state}/restore', [StateController::class, 'restore'])->withTrashed()->name('states.restore');
        Route::delete('states/{state}/force-delete', [StateController::class, 'forceDelete'])->withTrashed()->name('states.force-delete');
        Route::resource('states', StateController::class)->withTrashed();

        // City Module
        Route::get('cities-trash', [CityController::class, 'trash'])->name('cities.trash');
        Route::patch('cities/{city}/restore', [CityController::class, 'restore'])->withTrashed()->name('cities.restore');
        Route::delete('cities/{city}/force-delete', [CityController::class, 'forceDelete'])->withTrashed()->name('cities.force-delete');
        Route::resource('cities', CityController::class)->withTrashed();

        // Company Section ---------------------------------------------------------------------------------------------------------
        // Company Size Module
        Route::get('company-sizes-trash', [CompanySizeController::class, 'trash'])->name('company-sizes.trash');
        Route::patch('company-sizes/{companySize}/restore', [CompanySizeController::class, 'restore'])->withTrashed()->name('company-sizes.restore');
        Route::delete('company-sizes/{companySize}/force-delete', [CompanySizeController::class, 'forceDelete'])->withTrashed()->name('company-sizes.force-delete');
        Route::resource('company-sizes', CompanySizeController::class)->parameters(['company-sizes' => 'companySize'])->withTrashed();

        // Company Type
        Route::get('company-types-trash', [CompanyTypeController::class, 'trash'])->name('company-types.trash');
        Route::patch('company-types/{companyType}/restore', [CompanyTypeController::class, 'restore',])->name('company-types.restore');
        Route::delete('company-types/{companyType}/force-delete', [CompanyTypeController::class, 'forceDelete',])->name('company-types.force-delete');
        Route::resource('company-types', CompanyTypeController::class)->parameters(['company-types' => 'companyType'])->withTrashed();

        // Ownership Types
        Route::get('ownership-types/trash', [OwnershipTypeController::class, 'trash'])->name('ownership-types.trash');
        Route::patch('ownership-types/{id}/restore', [OwnershipTypeController::class, 'restore'])->whereNumber('id')->name('ownership-types.restore');
        Route::delete('ownership-types/{id}/force-delete', [OwnershipTypeController::class, 'forceDelete'])->whereNumber('id')->name('ownership-types.force-delete');
        Route::resource('ownership-types', OwnershipTypeController::class);

        // Business Models
        Route::get('business-models/trash', [BusinessModelController::class, 'trash'])->name('business-models.trash');
        Route::patch('business-models/{id}/restore', [BusinessModelController::class, 'restore'])->whereNumber('id')->name('business-models.restore');
        Route::delete('business-models/{id}/force-delete', [BusinessModelController::class, 'forceDelete'])->whereNumber('id')->name('business-models.force-delete');
        Route::resource('business-models', BusinessModelController::class);

        // Technologies
        Route::get('technology/trash', [TechnologyController::class, 'trash'])->name('technology.trash');
        Route::patch('technology/{id}/restore', [TechnologyController::class, 'restore'])->whereNumber('id')->name('technology.restore');
        Route::delete('technology/{id}/force-delete', [TechnologyController::class, 'forceDelete'])->whereNumber('id')->name('technology.force-delete');
        Route::resource('technology', TechnologyController::class);

        // Companies
        Route::get('companies/trash', [CompanyController::class, 'trash'])->name('companies.trash');
        Route::patch('companies/{id}/restore', [CompanyController::class, 'restore'])->whereNumber('id')->name('companies.restore');
        Route::delete('companies/{id}/force-delete', [CompanyController::class, 'forceDelete'])->whereNumber('id')->name('companies.force-delete');
        Route::resource('companies', CompanyController::class);

});