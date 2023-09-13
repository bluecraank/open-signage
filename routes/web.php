<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\SlideController;
use Illuminate\Support\Facades\Route;

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


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

    Route::get('/home', function() {
        // Redirect to /
        return redirect('/');
    });

    Route::prefix('devices')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('devices.index');

        Route::get('/create', [DeviceController::class, 'create'])->name('devices.create');

        Route::post('/', [DeviceController::class, 'store'])->name('devices.store');

        Route::get('/register', function() {
            return view('devices.register');
        });

        Route::post('/register', [DeviceController::class, 'register'])->name('devices.register');


        Route::get('/{id}', [DeviceController::class, 'show'])->name('devices.show');
        Route::put('/{id}', [DeviceController::class, 'update'])->name('devices.update');

        Route::delete('/{id}', [DeviceController::class, 'destroy'])->name('devices.destroy');
    });

    Route::prefix('presentations')->group(function () {
        Route::get('/', [PresentationController::class, 'index'])->name('presentations.index');
        Route::get('/create', [PresentationController::class, 'create'])->name('presentations.create');
        Route::post('/', [PresentationController::class, 'store'])->name('presentations.store');
        Route::get('/{id}', [PresentationController::class, 'show'])->name('presentations.show');
        Route::put('/{id}', [PresentationController::class, 'update'])->name('presentations.update');
        Route::delete('/{id}', [PresentationController::class, 'destroy'])->name('presentations.destroy');
    });

    Route::prefix('slides')->group(function () {
        Route::get('/create', [SlideController::class, 'create'])->name('slides.create');
        Route::post('/', [SlideController::class, 'store'])->name('slides.store');
        Route::get('/{id}', [SlideController::class, 'show'])->name('slides.show');
        Route::put('/{id}', [SlideController::class, 'update'])->name('slides.update');
        Route::delete('/{id}', [SlideController::class, 'destroy'])->name('slides.destroy');
    });
});

Route::get('/monitor/{secret}', [MonitorController::class, 'show'])->name('devices.monitor');

Auth::routes();


