<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\AuthControllerV2;
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

Route::post('login', [AuthControllerV2::class, 'authenticate']);
Route::post('register', [AuthControllerV2::class, 'register']);

Route::group(['middleware' => ['jwt.verify:ADMIN,USER,COMPANY,admin,user,company']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
});

Route::group(['middleware' => ['jwt.verify:COMPANY,company']], function() {
    Route::get('logout', [ApiController::class, 'logout']);

    Route::get('company/job', [JobController::class, 'fetchAllJobs']);
    Route::post('company/job', [JobController::class, 'createJob']);
    Route::patch('company/job/active-status', [JobController::class, 'updateActiveStatus']);
    Route::get('company/job/{jobId}/read', [JobController::class, 'fetchJobById']);
    Route::delete('company/job', [JobController::class, 'deleteJobs']);

    Route::get('company/job-applicant', [JobApplicationController::class, 'fetchCompanyApplicants']);
    Route::patch('company/job-applicant/status', [JobApplicationController::class, 'updateStatus']);
    Route::get('company/job-applicant/filter', [JobApplicationController::class, 'fetchApplicantsByFilter']);
});


// Route::get('company/job', ['middleware' => 'jwt.verify:admin,user,company', 'uses' => 'JobController@fetchAllJobs', 'as' => 'jobs']);
