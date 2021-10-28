<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AdminApiController;
use App\Http\Controllers\StudentApiController;
use App\Http\Controllers\TeacherApiController;

use App\Notifications\NotificationToTeacherForAssignedStudent;
use App\Models\User;
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

//Admin  API
Route::post('/login', [AdminApiController::class, 'login']);
Route::group(['prefix' => 'admin', 'middleware' => 'auth:api'], function () {
    Route::post('logout', [AdminApiController::class, 'logout']);
    Route::get('profile', [AdminApiController::class, 'profile']);
    Route::post('studentassigned', [AdminApiController::class, 'studentassignedtoteacher']);
    Route::post('approveduserbyadmin', [AdminApiController::class, 'approveduserbyadmin']);
    Route::get('userlist', [AdminApiController::class, 'userlist']);
});

// Student API
Route::post('/student/register', [StudentApiController::class, 'register']);
Route::group(['prefix' => 'student', 'middleware' => ['auth:api','studentAuth']], function () {
    Route::post('logout', [StudentApiController::class, 'logout']);
    Route::get('profile', [StudentApiController::class, 'profile']);
    Route::put('updateprofile/{userId}', [StudentApiController::class, 'updateprofile']);
    Route::delete('delete/{userId}', [StudentApiController::class, 'delete']);
    Route::put('updatepassword/{userId}', [StudentApiController::class, 'updatepassword']);
    Route::post('updateprofilepicture', [StudentApiController::class, 'updateprofilepicture']); 
    
});

//Tutor API
Route::post('/teacher/register', [TeacherApiController::class, 'register']);
Route::group(['prefix' => 'teacher', 'middleware' => ['auth:api','teacherAuth']], function () {
    Route::post('logout', [TeacherApiController::class, 'logout']);
    Route::get('profile', [TeacherApiController::class, 'profile']);
    Route::put('updateprofile/{userId}', [TeacherApiController::class, 'updateprofile']);
    Route::delete('delete/{userId}', [TeacherApiController::class, 'delete']);
    Route::put('updatepassword/{userId}', [TeacherApiController::class, 'updatepassword']);
    Route::post('updateprofilepicture', [TeacherApiController::class, 'updateprofilepicture']);
    
});