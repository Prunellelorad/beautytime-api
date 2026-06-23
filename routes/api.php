<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SalonController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\API\AdminController;

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/salons',    [SalonController::class, 'index']);
Route::get('/salons/{id}', [SalonController::class, 'show']);

// Routes protégées clients
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',              [AuthController::class, 'logout']);
    Route::get('/reservations',         [ReservationController::class, 'index']);
    Route::post('/reservations',        [ReservationController::class, 'store']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
});

// Routes Admin — protégées par is_admin seulement
Route::middleware('is_admin')->group(function () {
    Route::get('/admin/dashboard',                [AdminController::class, 'dashboard']);
    Route::get('/admin/clients',                  [AdminController::class, 'clients']);
    Route::delete('/admin/clients/{id}',          [AdminController::class, 'deleteClient']);
    Route::get('/admin/salons',                   [AdminController::class, 'salons']);
    Route::post('/admin/salons',                  [AdminController::class, 'storeSalon']);
    Route::delete('/admin/salons/{id}',           [AdminController::class, 'deleteSalon']);
    Route::get('/admin/reservations',             [AdminController::class, 'reservations']);
    Route::put('/admin/reservations/{id}/statut', [AdminController::class, 'updateStatutReservation']);
    Route::post('/admin/create-admin',            [AdminController::class, 'createAdmin']);
});