<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobApplicationController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('company/job', [JobController::class, 'fetch_all_jobs']);
Route::post('company/job', [JobController::class, 'create_job']);
Route::patch('company/job/active-status', [JobController::class, 'update_active_status']);
Route::get('company/job/{jobId}/read', [JobController::class, 'fetch_job_by_id']);
Route::delete('company/job', [JobController::class, 'delete_jobs']);

Route::get('company/job-applicant', [JobApplicationController::class, 'fetch_company_applicants']);
Route::patch('company/job-applicant/status', [JobApplicationController::class, 'update_status']);
Route::get('company/job-applicant/filter', [JobApplicationController::class, 'fetch_applicants_by_filter']);
