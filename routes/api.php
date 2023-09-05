<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\OffreController;
use App\Http\Controllers\API\FileUploadController;
use App\Http\Controllers\API\PasswordResetController; 
use App\Http\Controllers\API\ConfigController; 
use App\Http\Controllers\API\StatistiquesController; 
use App\Http\Controllers\API\VehiculeController; 
use App\Http\Controllers\API\TransporteurController;  
use App\Http\Controllers\API\DeviController; 
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('client/login', [RegisterController::class, 'clientLogin']);
Route::post('transporteur/login', [RegisterController::class, 'transporteurLogin']);

Route::post('client/register', [RegisterController::class, 'clientRegister']); 
Route::post('transporteur/register', [RegisterController::class, 'transporteurRegister']); 

 // Forgot Password
 Route::post('forgot-password',[PasswordResetController::class, 'forgotPassword']);
 // Reset Password
 Route::post('reset-password', [PasswordResetController::class, 'resetPassword'] ); 
 // Reset Password
 Route::post('check-otp', [PasswordResetController::class, 'checkOtp'] );
 //sendOtpCheckMail
 Route::post('sendOtpCheckMail', [UserController::class, 'sendOtpCheckMail'] );   

Route::post('upload', [FileUploadController::class, 'upload']); 
Route::get('download/{folder}/{filename}', [FileUploadController::class, 'download'])->name('download'); 
Route::delete('remove/{folder}/{filename}', [FileUploadController::class, 'remove']);

Route::middleware('auth:api')->group( function () {
    Route::post('logout', [RegisterController::class, 'logout']);
    Route::get('me', [UserController::class, 'me']);
});
Route::prefix('/transporteur')->middleware(['auth:api','role:ROLE_TRANSPORTEUR'])->group(function () {
    Route::get('/vehicules', [VehiculeController::class, 'index']);
    Route::post('/vehicules', [VehiculeController::class, 'store']);
    Route::get('/vehicules/{id}', [VehiculeController::class, 'show']);
    Route::put('/vehicules/{id}', [VehiculeController::class, 'update']);
    Route::delete('/vehicules/{id}', [VehiculeController::class, 'destroy']);

    Route::get('getTransporteurDetails', [TransporteurController::class, 'getTransporteurDetails']);
    Route::post('updateTransporteur', [TransporteurController::class, 'updateTransporteur']);
    //devi
    Route::post('addDevi', [DeviController::class, 'addDevi']);
    Route::put('devi/{deviId}', [DeviController::class, 'updateDevi']);
    Route::post('accept-devi', [DeviController::class, 'acceptDevi']);
});
Route::prefix('/client')->middleware(['auth:api','role:ROLE_CLIENT'])->group(function () {
    Route::get('offres/{statuts?}', [OffreController::class, 'index']);
    Route::get('offre/{id}', [OffreController::class, 'show']);
    Route::post('offres', [OffreController::class, 'store']); 

    //statistiques
    Route::get('statistiques', [StatistiquesController::class, 'statistiques']);
});



Route::prefix('/config')->group(function () {
    Route::get('villes', [ConfigController::class, 'AllVilles']);
    Route::get('villes/{ville}', [ConfigController::class, 'GetVille']);

    Route::get('categorie', [ConfigController::class, 'getCategorie']);

    Route::get('vehicletypes', [ConfigController::class, 'VehicleType']);
});

 


