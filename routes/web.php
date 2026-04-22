<?php

use App\Http\Controllers\Admin\Academic\CourseController;
use App\Http\Controllers\Admin\Academic\ExamController;
use App\Http\Controllers\Admin\Academic\QuestionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';




// Route::get('/admin/dashboard', function () {
//     return view('admin.dashboard');
// })->middleware(['auth:admin', 'verified'])->name('admin.dashboard');

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/profile', [ProfileController::class, 'editAdmin'])->name('admin.profile.edit');
    Route::patch('/admin/profile', [ProfileController::class, 'updateAdmin'])->name('admin.profile.update');
    Route::delete('/admin/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');
});

// Admin
Route::prefix('admin')->middleware('auth:admin')->group(function () {

    // Dashboard
    Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
        Route::get('/', 'dashboard')->name('admin.dashboard');
    });

    // Academic Routes
    Route::prefix('academic')->group(function () {

        // Academic Dashboard
        Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
            Route::get('/', 'academicDashboard')->name('admin.academic.dashboard');
        });

        // Courses
        Route::prefix('courses')->controller(CourseController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.courses.index');
            Route::get('/create', 'create')->name('admin.academic.courses.create');
            Route::post('/store', 'store')->name('admin.academic.courses.store');
            Route::get('/edit/id={course}', 'edit')->name('admin.academic.courses.edit');
            Route::put('/id={course}', 'update')->name('admin.academic.courses.update');
            Route::delete('/id={course}', 'destroy')->name('admin.academic.courses.destroy');
        });

        // Exams
        Route::prefix('exams')->controller(ExamController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.exams.index');
            Route::get('/create', 'create')->name('admin.academic.exams.create');
            Route::post('/store', 'store')->name('admin.academic.exams.store');
            Route::get('/edit/id={exam}', 'edit')->name('admin.academic.exams.edit');
            Route::put('/id={exam}', 'update')->name('admin.academic.exams.update');
            Route::delete('/id={exam}', 'destroy')->name('admin.academic.exams.destroy');
            Route::get('/question_paper/id={exam}', 'question_paper')->name('admin.academic.exams.question_paper');
        });

        // Questions
        Route::prefix('questions')->controller(QuestionController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.questions.index');
            Route::get('/create', 'create')->name('admin.academic.questions.create');
            Route::post('/store', 'store')->name('admin.academic.questions.store');
            Route::get('/edit/id={question}', 'edit')->name('admin.academic.questions.edit');
            Route::put('/id={question}', 'update')->name('admin.academic.questions.update');
            Route::delete('/id={question}', 'destroy')->name('admin.academic.questions.destroy');
        });

        // Students
        Route::prefix('students')->controller(StudentController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.students.index');
            Route::get('/create', 'create')->name('admin.academic.students.create');
            Route::post('/store', 'store')->name('admin.academic.students.store');
            Route::get('/edit/id={student}', 'edit')->name('admin.academic.students.edit');
            Route::put('/id={student}', 'update')->name('admin.academic.students.update');
            Route::delete('/id={student}', 'destroy')->name('admin.academic.students.destroy');
        });

    });

    // Corporate Routes
    Route::prefix('corporate')->group(function () {

        // Corporate Dashboard
        Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
            Route::get('/', 'corporateDashboard')->name('admin.corporate.dashboard');
        });

    });

});

require __DIR__.'/adminauth.php';
