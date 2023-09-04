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

Route::post('login', [RegisterController::class, 'login']);
Route::post('client/register', [ClientController::class, 'register']); 

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
Route::prefix('/client')->middleware(['auth:api','role:ROLE_CLIENT'])->group(function () {
    Route::get('offres/{statuts?}', [OffreController::class, 'index']);
    Route::get('offre/{id}', [OffreController::class, 'show']);
    Route::post('offres', [OffreController::class, 'store']); 

    //statistiques
    Route::get('statistiques', [StatistiquesController::class, 'statistiques']);
});
Route::prefix('/config')->group(function () {
    Route::get('categorie', [ConfigController::class, 'getCategorie']);
});


