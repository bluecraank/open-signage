<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Artisan;
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

// Route::post('/devices/register', [DeviceController::class, 'register']);

Route::get('/monitor/{secret}', [MonitorController::class, 'show'])->name('devices.monitor');

Route::get('/discover', [DeviceController::class, 'discover'])->name('devices.discover');

Route::middleware(['auth:sanctum', 'check_for_first_user'])->group(function () {
    Route::get('/', function () {
        // Redirect to /
        return redirect('/devices');
    });

    Route::get('/home', function() {
        // Redirect to /
        return redirect('/devices');
    });

    Route::prefix('devices')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('devices.index');

        Route::get('/create', [DeviceController::class, 'create'])->middleware('can:create devices')->name('devices.create');

        Route::post('/', [DeviceController::class, 'store'])->middleware('can:create devices')->name('devices.store');

        Route::post('/register', [DeviceController::class, 'register'])->middleware('can:register devices')->name('devices.register.accept');

        Route::get('/{id}', [DeviceController::class, 'show'])->middleware('can:read devices')->name('devices.show');

        Route::put('/{id}', [DeviceController::class, 'update'])->middleware('can:update devices')->name('devices.update');

        Route::put('/{id}/reload', [DeviceController::class, 'force_reload'])->middleware('can:force reload monitor')->name('devices.reload');

        Route::delete('/{id}', [DeviceController::class, 'destroy'])->middleware('can:delete devices')->name('devices.destroy');
    });

    Route::prefix('presentations')->group(function () {
        Route::get('/', [PresentationController::class, 'index'])->name('presentations.index');
        Route::post('/ongoing/{id}', [PresentationController::class, 'stopOngoingProcessing'])->name('presentations.ongoing.stop');
        Route::get('/ongoing', [PresentationController::class, 'ongoing'])->name('presentations.ongoing');
        Route::get('/create', [PresentationController::class, 'create'])->middleware('can:create presentations')->name('presentations.create');
        Route::post('/', [PresentationController::class, 'store'])->middleware('can:create presentations')->name('presentations.store');
        Route::get('/{id}', [PresentationController::class, 'show'])->middleware('can:read presentations')->name('presentations.show');
        Route::put('/{id}', [PresentationController::class, 'update'])->middleware('can:update presentations')->name('presentations.update');
        Route::delete('/{id}', [PresentationController::class, 'destroy'])->middleware('can:delete presentations')->name('presentations.destroy');
    });

    Route::prefix('slides')->group(function () {
        Route::delete('/{id}', [SlideController::class, 'destroy'])->middleware('can:delete slides')->name('slides.destroy');
    });

    Route::prefix('groups')->group(function () {
        Route::get('/', [GroupController::class, 'index'])->name('groups.index');
        Route::get('/create', [GroupController::class, 'create'])->middleware('can:create groups')->name('groups.create');
        Route::post('/', [GroupController::class, 'store'])->middleware('can:create groups')->name('groups.store');
        Route::get('/{id}', [GroupController::class, 'show'])->middleware('can:read groups')->name('groups.show');
        Route::put('/{id}', [GroupController::class, 'update'])->middleware('can:update groups')->name('groups.update');
        Route::delete('/{id}', [GroupController::class, 'destroy'])->middleware('can:delete groups')->name('groups.destroy');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->middleware('can:read users')->name('users.index');
        Route::get('/{id}', [UserController::class, 'show'])->middleware('can:read users')->name('users.show');
        Route::put('/{id}', [UserController::class, 'update'])->middleware('can:update users')->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('can:delete users')->name('users.destroy');
    });

    Route::prefix('schedules')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/create', [ScheduleController::class, 'create'])->middleware('can:create schedules')->name('schedules.create');
        Route::post('/', [ScheduleController::class, 'store'])->middleware('can:create schedules')->name('schedules.store');
        Route::get('/{id}', [ScheduleController::class, 'show'])->middleware('can:read schedules')->name('schedules.show');
        Route::put('/{id}', [ScheduleController::class, 'update'])->middleware('can:update schedules')->name('schedules.update');
        Route::delete('/{id}', [ScheduleController::class, 'destroy'])->middleware('can:delete schedules')->name('schedules.destroy');
    });

    Route::prefix('settings')->middleware('can:manage settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/', [SettingsController::class, 'update'])->name('settings.update');
    });

    Route::prefix('logs')->middleware('can:read logs')->group(function () {
        Route::get('/', [LogController::class, 'index'])->name('logs.index');
    });
});

Route::get('/test', function() {
    $id = 1;
    Artisan::call('presentation:process', [
        'id' => $id,
        'type' => 'pdf'
    ]);
});

Auth::routes();


