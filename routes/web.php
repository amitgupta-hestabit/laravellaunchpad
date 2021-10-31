
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    session()->flush();
   return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'adminAuth']], function () {
    Route::get('profile', [AdminDashboardController::class, 'editProfile'])->name('admin.profile');
    Route::post('profile', [AdminDashboardController::class, 'updateProfile'])->name('admin.update-profile');
    Route::get('dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('students', [AdminDashboardController::class, 'students'])->name('admin.students');
    Route::get('student/change-status/{userId}', [AdminDashboardController::class, 'changeStatus'])->name('admin.change-status');
    Route::get('teachers', [AdminDashboardController::class, 'teachers'])->name('admin.teachers');

    Route::get('student/edit/{userId}', [AdminDashboardController::class, 'editStudent'])->name('admin.edit-student');
    Route::get('teacher/edit/{userId}', [AdminDashboardController::class, 'editTeacher'])->name('admin.edit-teacher');
    route::patch('student/update/{userId}', [AdminDashboardController::class, 'updateStudent'])->name('admin.update-student');
    route::patch('teacher/update/{userId}', [AdminDashboardController::class, 'updateTeacher'])->name('admin.update-teacher');
    route::patch('student/approved/{userId}', [AdminDashboardController::class, 'approvedStudent'])->name('admin.approved-student');
    route::patch('teacher/approved/{userId}', [AdminDashboardController::class, 'approvedTeacher'])->name('admin.approved-teacher');
});

Route::group(['prefix' => 'teacher', 'middleware' => ['auth', 'teacherAuth']], function () {
    Route::get('profile', [TeacherDashboardController::class, 'editProfile'])->name('teacher.profile');
    Route::post('profile', [TeacherDashboardController::class, 'updateProfile'])->name('teacher.update-profile');
    Route::get('dashboard', [TeacherDashboardController::class, 'dashboard'])->name('teacher.dashboard');
    Route::post('dashboard', [TeacherDashboardController::class, 'updateTeacherProfile'])->name('update.teacher.profile');
});

Route::group(['prefix' => 'student', 'middleware' => ['auth', 'studentAuth']], function () {
    Route::get('profile', [StudentDashboardController::class, 'editProfile'])->name('student.profile');
    Route::post('profile', [StudentDashboardController::class, 'updateProfile'])->name('student.update-profile');
    Route::get('dashboard', [StudentDashboardController::class, 'dashboard'])->name('student.dashboard');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');