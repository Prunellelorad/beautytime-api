<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SalonController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\RealisationController;

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES
|--------------------------------------------------------------------------
*/
Route::post('/register',   [AuthController::class, 'register']);
Route::post('/login',      [AuthController::class, 'login']);

// Salons
Route::get('/salons',      [SalonController::class, 'index']);
Route::get('/salons/{id}', [SalonController::class, 'show']);

// Galerie / Réalisations d'un salon (Client)
Route::get('/salons/{salonId}/gallery',     [SalonController::class, 'getGallery']);
Route::get('/salons/{salonId}/realisations', [RealisationController::class, 'index']); // Conservé si utilisé
Route::get('/salon/{salonId}/reservations',  [ReservationController::class, 'salonReservations']);

/*
|--------------------------------------------------------------------------
| ROUTES PROTÉGÉES (CLIENTS & GÉRANTS)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth & Profil
    Route::post('/logout', [AuthController::class, 'logout']);

    // Gestion du salon du gérant
    Route::get('/my-salon', [SalonController::class, 'mySalon']);
    Route::post('/salons',  [SalonController::class, 'store']);

    // Publication & Suppression Galerie (Gérant)
    Route::post('/salons/{salonId}/gallery', [SalonController::class, 'uploadGallery']);
    Route::delete('/gallery/{id}',           [SalonController::class, 'deleteGalleryPhoto']);

    // Réservations Client
    Route::get('/reservations',         [ReservationController::class, 'index']);
    Route::post('/reservations',        [ReservationController::class, 'store']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);

    // Dashboard Gérant - Réservations
    Route::get('/gerant/reservations',        [ReservationController::class, 'managerIndex']);
    Route::patch('/reservations/{id}/statut', [ReservationController::class, 'updateStatus']);
    Route::patch('/reservations/{id}/status', [ReservationController::class, 'updateStatus']);

    // Dashboard Gérant - Réalisations (Ancienne route conservée)
    Route::post('/realisations', [RealisationController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| ROUTES ADMIN (SUPER ADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::get('/user/reservations', [ReservationController::class, 'getUserReservations']);
    Route::get('/admin/dashboard',                [AdminController::class, 'dashboard']);
    Route::get('/admin/clients',                  [AdminController::class, 'clients']);
    Route::delete('/admin/clients/{id}',          [AdminController::class, 'deleteClient']);
    Route::get('/admin/salons',                   [AdminController::class, 'salons']);
    Route::post('/admin/salons',                  [AdminController::class, 'storeSalon']);
    Route::delete('/admin/salons/{id}',           [AdminController::class, 'deleteSalon']);
    Route::get('/admin/reservations',             [AdminController::class, 'reservations']);
    Route::put('/admin/reservations/{id}/statut', [AdminController::class, 'updateStatutReservation']);
    Route::post('/admin/create-admin',            [AdminController::class, 'createAdmin']);
    Route::delete('/realisations/{id}', [RealisationController::class, 'destroy']);
    
});
Route::get('/user/reservations', function () {
    // Force le retour direct des données sans passer par le Filtre Auth
    return DB::table('reservations')->get(); 
});