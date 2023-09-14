<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/devices/register', function() {
    return view('devices.register');
})->name('devices.register');
Route::post('/devices/register', [DeviceController::class, 'register']);
Route::get('/monitor/{secret}', [MonitorController::class, 'show'])->name('devices.monitor');

Route::middleware(['auth:sanctum', 'check_for_first_user'])->group(function () {
    Route::get('/', [App\Http\Controllers\DeviceController::class, 'index']);

    Route::get('/home', function() {
        // Redirect to /
        return redirect('/');
    });

    Route::prefix('devices')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('devices.index');

        Route::get('/create', [DeviceController::class, 'create'])->middleware('can:create devices')->name('devices.create');

        Route::post('/', [DeviceController::class, 'store'])->middleware('can:create devices')->name('devices.store');

        Route::get('/{id}', [DeviceController::class, 'show'])->middleware('can:read devices')->name('devices.show');

        Route::put('/{id}', [DeviceController::class, 'update'])->middleware('can:update devices')->name('devices.update');

        Route::put('/{id}/reload', [DeviceController::class, 'force_reload'])->middleware('can:force reload monitor')->name('devices.reload');

        Route::delete('/{id}', [DeviceController::class, 'destroy'])->middleware('can:delete devices')->name('devices.destroy');
    });

    Route::prefix('presentations')->group(function () {
        Route::get('/', [PresentationController::class, 'index'])->name('presentations.index');
        Route::get('/create', [PresentationController::class, 'create'])->middleware('can:create presentations')->name('presentations.create');
        Route::post('/', [PresentationController::class, 'store'])->middleware('can:create presentations')->name('presentations.store');
        Route::get('/{id}', [PresentationController::class, 'show'])->middleware('can:read presentations')->name('presentations.show');
        Route::put('/{id}', [PresentationController::class, 'update'])->middleware('can:update presentations')->name('presentations.update');
        Route::delete('/{id}', [PresentationController::class, 'destroy'])->middleware('can:delete presentations')->name('presentations.destroy');
    });

    Route::prefix('slides')->group(function () {
        // Route::get('/create', [SlideController::class, 'create'])->name('slides.create');
        // Route::post('/', [SlideController::class, 'store'])->name('slides.store');
        // Route::get('/{id}', [SlideController::class, 'show'])->name('slides.show');
        // Route::put('/{id}', [SlideController::class, 'update'])->name('slides.update');
        Route::delete('/{id}', [SlideController::class, 'destroy'])->middleware('can:delete slides')->name('slides.destroy');
    });

    Route::prefix('groups')->group(function () {
        Route::get('/', [GroupController::class, 'index'])->name('groups.index');
        Route::get('/create', [GroupController::class, 'create'])->name('groups.create');
        Route::post('/', [GroupController::class, 'store'])->name('groups.store');
        Route::get('/{id}', [GroupController::class, 'show'])->name('groups.show');
        Route::put('/{id}', [GroupController::class, 'update'])->name('groups.update');
        Route::delete('/{id}', [GroupController::class, 'destroy'])->name('groups.destroy');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        // Route::get('/create', [UserController::class, 'create'])->name('users.create');
        // Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

Auth::routes();


