<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransporteurController;
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


Route::middleware(['auth.custom','role:ROLE_ADMIN'])->group(function () {
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/deactivate-client/{id}', [ClientController::class, 'desactiver'])->name('desactiver.client');

    Route::get('/transporteurs', [TransporteurController::class, 'index'])->name('transporteurs.index');
    Route::get('/deactivate-transporteur/{id}', [TransporteurController::class, 'desactiver'])->name('desactiver.transporteur');
    Route::get('/transporteurs/{id}/toggle-approuver', [TransporteurController::class, 'toggleApprouver'])->name('transporteurs.toggleApprouver');
    Route::get('/offres', [OffreController::class, 'index'])->name('offres.index');
    Route::get('/devis/{IdDemande}', [OffreController::class, 'listeDevis'])->name('liste.devis.by.deamnde');
    Route::get('/devisChat/{deviId}', [OffreController::class, 'ChatDevi'])->name('liste.chat');

    Route::get('/change-status-offre/{id}/{status}', [OffreController::class, 'changeStatusOffre'])->name('change.status.offre');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});


Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

