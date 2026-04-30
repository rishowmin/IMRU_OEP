<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\Academic\CourseController;
use App\Http\Controllers\Admin\Academic\EnrollmentController;
use App\Http\Controllers\Admin\Academic\ExamAttemptController;
use App\Http\Controllers\Admin\Academic\ExamController;
use App\Http\Controllers\Admin\Academic\ExamRuleController;
use App\Http\Controllers\Admin\Academic\QuestionController;
use App\Http\Controllers\Admin\Academic\ReviewAnswerController;
use App\Http\Controllers\Admin\Academic\StudentController;
use App\Http\Controllers\Admin\Academic\TeacherController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\MyExamController;
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

Route::get('/academic/login', function () {
    return view('auth_view.academic.login');
})->name('academic.login');

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
    Route::prefix('dashboard')->controller(App\Http\Controllers\Admin\DashboardController::class)->group(function () {
        Route::get('/', 'dashboard')->name('admin.dashboard');
    });

    // Academic Routes
    Route::prefix('academic')->group(function () {

        // Academic Dashboard
        Route::prefix('dashboard')->controller(App\Http\Controllers\Admin\DashboardController::class)->group(function () {
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
            Route::get('/question-paper/id={exam}', 'questionPaper')->name('admin.academic.exams.questionPaper');

            Route::get('/settings/id={exam}', 'examSettings')->name('admin.academic.exams.settings');
            Route::put('/settings/id={exam}', 'updateExamSettings')->name('admin.academic.exams.settings.update');
        });

        // Exams Rules
        Route::prefix('exam-rules')->controller(ExamRuleController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.examRules.index');
            Route::get('/create', 'create')->name('admin.academic.examRules.create');
            Route::post('/store', 'store')->name('admin.academic.examRules.store');
            Route::get('/edit/id={examRule}', 'edit')->name('admin.academic.examRules.edit');
            Route::put('/id={examRule}', 'update')->name('admin.academic.examRules.update');
            Route::delete('/id={examRule}', 'destroy')->name('admin.academic.examRules.destroy');
        });

        // Exams Attempts
        Route::prefix('exam-attempts')->controller(ExamAttemptController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.examAttempts.index');
            Route::post('/id={attempt}/reset', 'reset')->name('admin.examAttempts.reset');
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

        // Teacher
        Route::prefix('teachers')->controller(TeacherController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.teachers.index');
            Route::get('/create', 'create')->name('admin.academic.teachers.create');
            Route::post('/store', 'store')->name('admin.academic.teachers.store');
            Route::get('/edit/id={teacher}', 'edit')->name('admin.academic.teachers.edit');
            Route::put('/id={teacher}', 'update')->name('admin.academic.teachers.update');
            Route::delete('/id={teacher}', 'destroy')->name('admin.academic.teachers.destroy');

            // Teacher Profile
            Route::prefix('teacher-profile')->group(function () {
                Route::get('/id={teacher}', 'teacherProfile')->name('admin.academic.teachers.profile');
                Route::post('/store/id={teacher}', 'teacherProfileStore')->name('admin.academic.teachers.profile.store');
            });
        });

        // Students
        Route::prefix('students')->controller(StudentController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.students.index');
            Route::get('/create', 'create')->name('admin.academic.students.create');
            Route::post('/store', 'store')->name('admin.academic.students.store');
            Route::get('/edit/id={student}', 'edit')->name('admin.academic.students.edit');
            Route::put('/id={student}', 'update')->name('admin.academic.students.update');
            Route::delete('/id={student}', 'destroy')->name('admin.academic.students.destroy');

            // Student Profile
            Route::prefix('student-profile')->group(function () {
                Route::get('/id={student}', 'studentProfile')->name('admin.academic.students.profile');
                Route::post('/store/id={student}', 'studentProfileStore')->name('admin.academic.students.profile.store');
            });
        });

        // Enrollments
        Route::prefix('enrollments')->controller(EnrollmentController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.enrollments.index');
            Route::post('/', 'store')->name('admin.academic.enrollments.store');
            Route::get('/edit/id={enroll}', 'edit')->name('admin.academic.enrollments.edit');
            Route::put('/id={enroll}', 'update')->name('admin.academic.enrollments.update');
            Route::delete('/id={enroll}', 'destroy')->name('admin.academic.enrollments.destroy');
        });

        // Review Answer
        Route::prefix('review-answer')->controller(ReviewAnswerController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.reviewAnswer.index');
            Route::get('/exam/id={exam}', 'show')->name('admin.academic.reviewAnswer.show');
            Route::get('/exam/id={exam}/student/id={student}', 'studentAnswers')->name('admin.academic.reviewAnswer.studentAnswers');
            Route::post('/store/exam/id={exam}/student/id={student}', 'storeReview')->name('admin.academic.reviewAnswer.store');
        });

    });

    // Professional Routes
    Route::prefix('professional')->group(function () {

        // Professional Dashboard
        Route::prefix('dashboard')->controller(App\Http\Controllers\Admin\DashboardController::class)->group(function () {
            Route::get('/', 'professionalDashboard')->name('admin.professional.dashboard');
        });

    });

});

require __DIR__.'/adminauth.php';



// Student
Route::prefix('student')->middleware('auth:student')->group(function () {

    // Dashboard
    Route::prefix('dashboard')->controller(App\Http\Controllers\Student\DashboardController::class)->group(function () {
        Route::get('/', 'dashboard')->name('student.dashboard');
    });

    // My Exams
    Route::prefix('myExams')->controller(MyExamController::class)->group(function () {
        Route::get('/', 'index')->name('student.myExams');
        Route::get('/details/id={exam}', 'show')->name('student.myExams.show');
        Route::get('/answer-sheet/id={exam}', 'startExam')->name('student.myExams.start');
        Route::post('/store-answer/id={exam}', 'storeAnswer')->name('student.myExams.store');
        Route::get('/view-result/id={exam}', 'viewResult')->name('student.myExams.result');
        Route::get('/rules/id={exam}', 'examRules')->name('student.myExams.rule');
    });

});

require __DIR__.'/studentauth.php';



// Teacher
Route::prefix('teacher')->middleware('auth:teacher')->group(function () {

    // Dashboard
    Route::prefix('dashboard')->controller(App\Http\Controllers\Teacher\DashboardController::class)->group(function () {
        Route::get('/', 'dashboard')->name('teacher.dashboard');
    });

    // Courses
    Route::prefix('courses')->controller(App\Http\Controllers\Teacher\CourseController::class)->group(function () {
        Route::get('/', 'index')->name('teacher.courses.index');
        Route::get('/create', 'create')->name('teacher.courses.create');
        Route::post('/store', 'store')->name('teacher.courses.store');
        Route::get('/edit/id={course}', 'edit')->name('teacher.courses.edit');
        Route::put('/id={course}', 'update')->name('teacher.courses.update');
        Route::delete('/id={course}', 'destroy')->name('teacher.courses.destroy');
    });

});

require __DIR__.'/teacherauth.php';
