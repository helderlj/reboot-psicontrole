<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\ServiceUsersController;
use App\Http\Controllers\Api\UserPatientsController;
use App\Http\Controllers\Api\UserServicesController;
use App\Http\Controllers\Api\UserSchedulesController;
use App\Http\Controllers\Api\UserAppointmentsController;
use App\Http\Controllers\Api\ServiceAppointmentsController;
use App\Http\Controllers\Api\PatientAppointmentsController;
use App\Http\Controllers\Api\ScheduleAppointmentsController;
use App\Http\Controllers\Api\ScheduleScheduleUnavailabilitiesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

Route::name('api.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('services', ServiceController::class);

        // Service Appointments
        Route::get('/services/{service}/appointments', [
            ServiceAppointmentsController::class,
            'index',
        ])->name('services.appointments.index');
        Route::post('/services/{service}/appointments', [
            ServiceAppointmentsController::class,
            'store',
        ])->name('services.appointments.store');

        // Service Users
        Route::get('/services/{service}/users', [
            ServiceUsersController::class,
            'index',
        ])->name('services.users.index');
        Route::post('/services/{service}/users/{user}', [
            ServiceUsersController::class,
            'store',
        ])->name('services.users.store');
        Route::delete('/services/{service}/users/{user}', [
            ServiceUsersController::class,
            'destroy',
        ])->name('services.users.destroy');

        Route::apiResource('schedules', ScheduleController::class);

        // Schedule Schedule Unavailabilities
        Route::get('/schedules/{schedule}/schedule-unavailabilities', [
            ScheduleScheduleUnavailabilitiesController::class,
            'index',
        ])->name('schedules.schedule-unavailabilities.index');
        Route::post('/schedules/{schedule}/schedule-unavailabilities', [
            ScheduleScheduleUnavailabilitiesController::class,
            'store',
        ])->name('schedules.schedule-unavailabilities.store');

        // Schedule Appointments
        Route::get('/schedules/{schedule}/appointments', [
            ScheduleAppointmentsController::class,
            'index',
        ])->name('schedules.appointments.index');
        Route::post('/schedules/{schedule}/appointments', [
            ScheduleAppointmentsController::class,
            'store',
        ])->name('schedules.appointments.store');

        Route::apiResource('users', UserController::class);

        // User Schedules
        Route::get('/users/{user}/schedules', [
            UserSchedulesController::class,
            'index',
        ])->name('users.schedules.index');
        Route::post('/users/{user}/schedules', [
            UserSchedulesController::class,
            'store',
        ])->name('users.schedules.store');

        // User Patients
        Route::get('/users/{user}/patients', [
            UserPatientsController::class,
            'index',
        ])->name('users.patients.index');
        Route::post('/users/{user}/patients', [
            UserPatientsController::class,
            'store',
        ])->name('users.patients.store');

        // User Appointments
        Route::get('/users/{user}/appointments', [
            UserAppointmentsController::class,
            'index',
        ])->name('users.appointments.index');
        Route::post('/users/{user}/appointments', [
            UserAppointmentsController::class,
            'store',
        ])->name('users.appointments.store');

        // User Services
        Route::get('/users/{user}/services', [
            UserServicesController::class,
            'index',
        ])->name('users.services.index');
        Route::post('/users/{user}/services/{service}', [
            UserServicesController::class,
            'store',
        ])->name('users.services.store');
        Route::delete('/users/{user}/services/{service}', [
            UserServicesController::class,
            'destroy',
        ])->name('users.services.destroy');

        Route::apiResource('patients', PatientController::class);

        // Patient Appointments
        Route::get('/patients/{patient}/appointments', [
            PatientAppointmentsController::class,
            'index',
        ])->name('patients.appointments.index');
        Route::post('/patients/{patient}/appointments', [
            PatientAppointmentsController::class,
            'store',
        ])->name('patients.appointments.store');
    });
