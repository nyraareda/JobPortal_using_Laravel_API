<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\API\ResumeController;
use App\Http\Controllers\API\ApplicationController;
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

Route::get('users', [AuthController::class, 'index']);
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Routes accessible to authenticated users
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    // Routes accessible to job seekers
    Route::middleware('role:job_seeker')->group(function () {
        Route::apiResource('applications', ApplicationController::class)->except(['destroy']);
        Route::get('jobs', [JobController::class, 'index']);
        Route::get('jobs/{id}', [JobController::class, 'show']);
        Route::apiResource('resumes', ResumeController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    });

    // Routes accessible to employers
    Route::middleware('role:employer')->group(function () {
        Route::apiResource('jobs', JobController::class)->except(['index', 'show']);
        Route::apiResource('companies', CompanyController::class)->only(['index', 'show', 'store', 'update']);
        Route::get('applications', [ApplicationController::class, 'index']);
        Route::get('applications/{id}', [ApplicationController::class, 'show']);
    });

    // Routes accessible to administrators
    Route::middleware('role:administrator')->group(function () {
        Route::apiResource('companies', CompanyController::class)->except(['store', 'update']);
        Route::apiResource('jobs', JobController::class)->except(['store', 'update']);
        Route::apiResource('applications', ApplicationController::class)->except(['store', 'update']);
        Route::apiResource('resumes', ResumeController::class)->except(['store', 'update']);
    });

});