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
use App\Http\Controllers\API\OfferTransporteurController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\ReviewController;

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
Route::post('review', [ReviewController::class, 'store']);
Route::get('reviews', [ReviewController::class, 'index']);

Route::post('contact', [ContactController::class, 'store']);
Route::get('offres', [OffreController::class, 'indexPublic']);

Route::post('client/login', [RegisterController::class, 'clientLogin']);
Route::post('transporteur/login', [RegisterController::class, 'transporteurLogin']);

Route::post('client/register', [RegisterController::class, 'clientRegister']);
Route::post('transporteur/register', [RegisterController::class, 'transporteurRegister']);

Route::get('emailExists', [RegisterController::class, 'emailExists']);
Route::post('CheckMail', [UserController::class, 'CheckMail'] );

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
Route::get('download2', [FileUploadController::class, 'getPhotosAsFiles']);
Route::delete('remove/{folder}/{filename}', [FileUploadController::class, 'remove']);

Route::middleware('auth:api')->group( function () {
    Route::post('logout', [RegisterController::class, 'logout']);
    Route::get('me', [UserController::class, 'me']);
    Route::get('users/{id}', [UserController::class, 'getUserById']);
    Route::post('messages', [ChatController::class, 'createMessage']);
    Route::get('messages', [ChatController::class, 'MessageByDevis']);
    Route::get('notifications', [NotificationController::class, 'notification']);
    Route::post('notifications/{id}/delete', [NotificationController::class, 'readNotification']);
});
Route::prefix('/transporteur')->middleware(['auth:api','role:ROLE_TRANSPORTEUR'])->group(function () {
    Route::get('/vehicules', [VehiculeController::class, 'index']);
    Route::post('/vehicules', [VehiculeController::class, 'store']);
    Route::get('/vehicules/{id}', [VehiculeController::class, 'show']);
    Route::put('/vehicules/{id}', [VehiculeController::class, 'update']);
    Route::delete('/vehicules/{id}', [VehiculeController::class, 'destroy']);
    
    Route::get('{transporterId}/reviews', [ReviewController::class, 'getTransporterReviews']);
    Route::get('getTransporteurDetails', [TransporteurController::class, 'getTransporteurDetails']);
    //Route::post('updateTransporteur', [TransporteurController::class, 'updateTransporteur']);
    Route::put('account', [ClientController::class, 'update']);
    Route::post('change-password', [ClientController::class, 'resetPassword']);
    //devi
    Route::post('devis', [DeviController::class, 'addDevi']);
    Route::put('devis/{deviId}', [DeviController::class, 'updateDevi']);
    Route::get('devis/{deviId}', [DeviController::class, 'getDevisById']);
    Route::delete('devis/{deviId}', [DeviController::class, 'deleteDevis']);


    Route::get('devisByOffreId/{id}', [DeviController::class, 'getDevisByOffreId']);

    //offre
    Route::get('offres', [OfferTransporteurController::class, 'index']);
    Route::get('offres/{id}', [OfferTransporteurController::class, 'show']);
    //devi
    Route::get('devisByStatus/{status}', [DeviController::class, 'getDevi']);
    //if exist devis in offer bu id

});
Route::prefix('/client')->middleware(['auth:api','role:ROLE_CLIENT'])->group(function () {
    Route::get('offres', [OffreController::class, 'index']);
    Route::get('offresByStatus/{status}', [OffreController::class, 'offresByStatus']);
    Route::get('offre/{id}', [OffreController::class, 'show']);
    Route::put('offres/{id}', [OffreController::class, 'update']);
    Route::post('offres', [OffreController::class, 'store']);
    Route::post('offres/{id}/close', [OffreController::class, 'close']);
    Route::delete('offres/{id}', [OffreController::class, 'destroy']);
    //statistiques
    Route::get('statistiques', [StatistiquesController::class, 'statistiques']);
    //accept-devi
    Route::post('devis/accept', [DeviController::class, 'acceptDevi']);

    Route::post('devis/rejeter', [DeviController::class, 'rejeterDevi']);
    Route::post('devis/terminer', [DeviController::class, 'terminerDevi']);
    //get devi
    Route::get('devisByStatus/{status}',[DeviController::class, 'getDevisClientByStatus']);
    Route::get('devisByConnectedClient',[DeviController::class, 'getDevisByConnectedClient']);

    Route::get('devis/{id}', [DeviController::class, 'getDevisClientById']);
    Route::get('devisByOffreId/{id}', [DeviController::class, 'getDevisByOffreId2']);

    //modification informations client
    Route::put('account', [ClientController::class, 'update']);
    Route::post('change-password', [ClientController::class, 'resetPassword']);
});



Route::prefix('/config')->group(function () {
    Route::get('villes', [ConfigController::class, 'AllVilles']);
    Route::get('villes/{ville}', [ConfigController::class, 'GetVille']);

    Route::get('categorie', [ConfigController::class, 'getCategorie']);

    Route::get('vehicletypes', [ConfigController::class, 'VehicleType']);
});




