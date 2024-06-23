<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\JobController;
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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::apiResource('companies', CompanyController::class);
Route::apiResource('jobs', JobController::class);
// Routes accessible to authenticated users
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    
    // Routes accessible to job seekers
    Route::middleware('role:job seeker')->group(function () {
        // Define routes accessible to job seekers
    });

    // Routes accessible to employers
    Route::middleware('role:employer')->group(function () {
        // Define routes accessible to employers
    });

    // Routes accessible to administrators
    Route::middleware('role:administrator')->group(function () {
        // Define routes accessible to administrators
    });
});