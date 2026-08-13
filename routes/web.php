<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\General\CityController;
use App\Http\Controllers\General\CountryController;
use App\Http\Controllers\General\IndustryController;
use App\Http\Controllers\Job\CompanyController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});


// Guest Routes
Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
   
// Protected Routes
Route::middleware('auth')->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Country 
    Route::resource('countries', CountryController::class);
    // Route::resource('countries', CountryController::class)->except(['create', 'show']);

    // Industry
    Route::resource('industries', IndustryController::class)->except(['create', 'show']);

    // City
    Route::get('/cities', [CityController::class, 'index'])
        ->name('cities.index');

    Route::post('/cities', [CityController::class, 'store'])
        ->name('cities.store');

    Route::get('/cities/{country:slug}/{city:slug}/edit', [CityController::class, 'edit'])
        ->name('cities.edit');

    Route::put('/cities/{country:slug}/{city:slug}', [CityController::class, 'update'])
        ->name('cities.update');

    Route::delete('/cities/{country:slug}/{city:slug}', [CityController::class, 'destroy'])
        ->name('cities.destroy');

    // Company management (authenticated only)
    Route::resource('companies', CompanyController::class)->except(['index', 'show']);

    Route::get('/companies/cities', [CompanyController::class, 'cities'])
    ->name('companies.cities');

});

// Public Company Pages
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{company:slug}', [CompanyController::class, 'show'])->name('companies.show');