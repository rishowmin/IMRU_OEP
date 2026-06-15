<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\Academic\AcaCourseController;
use App\Http\Controllers\Admin\Academic\AcaEnrollmentController;
use App\Http\Controllers\Admin\Academic\AcaExamAttemptController;
use App\Http\Controllers\Admin\Academic\AcaExamController;
use App\Http\Controllers\Admin\Academic\AcaExamRuleController;
use App\Http\Controllers\Admin\Academic\AcaExamSetController;
use App\Http\Controllers\Admin\Academic\AcaPerformanceController;
use App\Http\Controllers\Admin\Academic\AcaProctoringController;
use App\Http\Controllers\Admin\Academic\AcaQuestionController;
use App\Http\Controllers\Admin\Academic\AcaQuestionLibraryController;
use App\Http\Controllers\Admin\Academic\AcaReviewAnswerController;
use App\Http\Controllers\Admin\Academic\AcaStudentController;
use App\Http\Controllers\Admin\Academic\AcaTeacherController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\StudDashboardController;
use App\Http\Controllers\Student\StudMiscController;
use App\Http\Controllers\Student\StudMyExamController;
use App\Http\Controllers\Student\StudMyResultController;
use App\Http\Controllers\Student\StudProctoringController;
use App\Http\Controllers\Student\StudProfileController;
use App\Http\Controllers\Teacher\TechCourseController;
use App\Http\Controllers\Teacher\TechDashboardController;
use App\Http\Controllers\Teacher\TechEnrollmentController;
use App\Http\Controllers\Teacher\TechExamAttemptController;
use App\Http\Controllers\Teacher\TechExamController;
use App\Http\Controllers\Teacher\TechExamSetController;
use App\Http\Controllers\Teacher\TechMiscController;
use App\Http\Controllers\Teacher\TechPerformanceController;
use App\Http\Controllers\Teacher\TechProctoringController;
use App\Http\Controllers\Teacher\TechProfileController;
use App\Http\Controllers\Teacher\TechQuestionController;
use App\Http\Controllers\Teacher\TechQuestionLibraryController;
use App\Http\Controllers\Teacher\TechReviewAnswerController;
use App\Http\Controllers\Teacher\TechStudentController;
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

Route::get('/admin/login', function () {
    return view('admin.auth.login');
})->name('admin.login');

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

        // Teacher
        Route::prefix('teachers')->controller(AcaTeacherController::class)->group(function () {
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
        Route::prefix('students')->controller(AcaStudentController::class)->group(function () {
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

        // Courses
        Route::prefix('courses')->controller(AcaCourseController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.courses.index');
            Route::get('/create', 'create')->name('admin.academic.courses.create');
            Route::post('/store', 'store')->name('admin.academic.courses.store');
            Route::get('/edit/id={course}', 'edit')->name('admin.academic.courses.edit');
            Route::put('/id={course}', 'update')->name('admin.academic.courses.update');
            Route::delete('/id={course}', 'destroy')->name('admin.academic.courses.destroy');
        });

        // Enrollments
        Route::prefix('enrollments')->controller(AcaEnrollmentController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.enrollments.index');
            Route::post('/', 'store')->name('admin.academic.enrollments.store');
            Route::delete('/id={enroll}', 'destroy')->name('admin.academic.enrollments.destroy');
            Route::delete('/course/id={course}', 'destroyCourse')->name('admin.academic.enrollments.destroyCourse');
        });

        // Exams
        Route::prefix('exams')->controller(AcaExamController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.exams.index');
            Route::get('/create', 'create')->name('admin.academic.exams.create');
            Route::post('/store', 'store')->name('admin.academic.exams.store');
            Route::get('/edit/id={exam}', 'edit')->name('admin.academic.exams.edit');
            Route::put('/id={exam}', 'update')->name('admin.academic.exams.update');
            Route::delete('/id={exam}', 'destroy')->name('admin.academic.exams.destroy');

            Route::get('/question-paper/id={exam}', 'questionPaper')->name('admin.academic.exams.questionPaper');
            Route::post('/question-paper/id={exam}/store', 'storeQuestion')->name('admin.academic.exams.questionPaper.store');
            Route::post('/id={exam}/question-paper/library', 'storeFromLibrary')->name('admin.academic.exams.questionPaper.library');
            Route::put('/question-paper/id={exam}/question/update/id={question}', 'updateQuestion')->name('admin.academic.exams.questionPaper.update');
            Route::delete('/question-paper/id={exam}/question/id={question}', 'destroyQuestion')->name('admin.academic.exams.questionPaper.destroy');

            Route::get('/settings/id={exam}', 'examSettings')->name('admin.academic.exams.settings');
            Route::put('/settings/id={exam}', 'updateExamSettings')->name('admin.academic.exams.settings.update');
        });

        // Exams Rules
        Route::prefix('exam-rules')->controller(AcaExamRuleController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.examRules.index');
            Route::get('/create', 'create')->name('admin.academic.examRules.create');
            Route::post('/store', 'store')->name('admin.academic.examRules.store');
            Route::get('/edit/id={examRule}', 'edit')->name('admin.academic.examRules.edit');
            Route::put('/id={examRule}', 'update')->name('admin.academic.examRules.update');
            Route::delete('/id={examRule}', 'destroy')->name('admin.academic.examRules.destroy');
        });

        // Exams Attempts
        Route::prefix('exam-attempts')->controller(AcaExamAttemptController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.examAttempts.index');
            Route::post('/id={attempt}/reset', 'reset')->name('admin.academic.examAttempts.reset');
        });

        // Questions
        Route::prefix('questions')->controller(AcaQuestionController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.questions.index');
            Route::get('/create', 'create')->name('admin.academic.questions.create');
            Route::post('/store', 'store')->name('admin.academic.questions.store');
            Route::get('/edit/id={question}', 'edit')->name('admin.academic.questions.edit');
            Route::put('/id={question}', 'update')->name('admin.academic.questions.update');
            Route::delete('/id={question}', 'destroy')->name('admin.academic.questions.destroy');
        });

        // Questions Library
        Route::prefix('questions-library')->controller(AcaQuestionLibraryController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.questions.library.index');
            Route::get('/create', 'create')->name('admin.academic.questions.library.create');
            Route::post('/store', 'store')->name('admin.academic.questions.library.store');
            Route::get('/edit/id={questionLib}', 'edit')->name('admin.academic.questions.library.edit');
            Route::put('/id={questionLib}', 'update')->name('admin.academic.questions.library.update');
            Route::delete('/id={questionLib}', 'destroy')->name('admin.academic.questions.library.destroy');
        });

        // AI Exam Sets
        Route::prefix('ai-exam-sets')->controller(AcaExamSetController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.aiExamSets.index');
            Route::get('/create', 'create')->name('admin.academic.aiExamSets.create');
            Route::post('/store', 'store')->name('admin.academic.aiExamSets.store');
            Route::get('/show/id={examSet}', 'show')->name('admin.academic.aiExamSets.show');
            Route::delete('/id={examSet}', 'destroy')->name('admin.academic.aiExamSets.destroy');

            Route::patch('/id={examSet}/status', 'updateStatus')->name('admin.academic.aiExamSets.status');
            Route::post('/id={examSet}/publish', 'publishToExam')->name('admin.academic.aiExamSets.publish');
            Route::post('/id={examSet}/update-marks', 'updateMarks')->name('admin.academic.aiExamSets.updateMarks');
        });

        // Review Answer
        Route::prefix('review-answer')->controller(AcaReviewAnswerController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.reviewAnswer.index');
            Route::get('/exam/id={exam}', 'show')->name('admin.academic.reviewAnswer.show');
            Route::get('/exam/id={exam}/student/id={student}', 'studentAnswers')->name('admin.academic.reviewAnswer.studentAnswers');
            Route::post('/store/exam/id={exam}/student/id={student}', 'storeReview')->name('admin.academic.reviewAnswer.store');
        });

        // Performance & Grading
        Route::prefix('performance')->controller(AcaPerformanceController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.performance.index');
            Route::get('/exam/id={exam}', 'examAnalytics')->name('admin.academic.performance.examAnalytics');
            Route::get('/exam/id={exam}/student/id={student}', 'studentReport')->name('admin.academic.performance.studentReport');
            Route::post('/store/exam/id={exam}/student/id={student}', 'storeReview')->name('admin.academic.performance.store');
        });

        // Proctoring Reports
        Route::prefix('proctoring')->controller(AcaProctoringController::class)->group(function () {
            Route::get('/', 'index')->name('admin.academic.proctoring.index');
            Route::get('/report/id={attempt}',  'getReport')->name('admin.academic.proctoring.report');
            Route::get('/summary/id={attempt}', 'getSummary')->name('admin.academic.proctoring.summary');
        });

    });

    // Professional Routes
    Route::prefix('professional')->group(function () {

        // Professional Dashboard
        Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
            Route::get('/', 'professionalDashboard')->name('admin.professional.dashboard');
        });

    });

});

require __DIR__.'/adminauth.php';



// Teacher
Route::prefix('teacher')->middleware('auth:teacher')->group(function () {

    // Dashboard
    Route::prefix('dashboard')->controller(TechDashboardController::class)->group(function () {
        Route::get('/', 'dashboard')->name('teacher.dashboard');
    });

    // Students
    Route::prefix('students')->controller(TechStudentController::class)->group(function () {
        Route::get('/', 'index')->name('teacher.students.index');
        Route::get('/create', 'create')->name('teacher.students.create');
        Route::post('/store', 'store')->name('teacher.students.store');
        Route::get('/edit/id={student}', 'edit')->name('teacher.students.edit');
        Route::put('/id={student}', 'update')->name('teacher.students.update');
        Route::delete('/id={student}', 'destroy')->name('teacher.students.destroy');

        // Student Profile
        Route::prefix('student-profile')->group(function () {
            Route::get('/id={student}', 'studentProfile')->name('teacher.students.profile');
            Route::post('/store/id={student}', 'studentProfileStore')->name('teacher.students.profile.store');
        });
    });

    // Courses
    Route::prefix('courses')->controller(TechCourseController::class)->group(function () {
        Route::get('/', 'index')->name('teacher.courses.index');
        Route::get('/create', 'create')->name('teacher.courses.create');
        Route::post('/store', 'store')->name('teacher.courses.store');
        Route::get('/edit/id={course}', 'edit')->name('teacher.courses.edit');
        Route::put('/id={course}', 'update')->name('teacher.courses.update');
        Route::delete('/id={course}', 'destroy')->name('teacher.courses.destroy');
    });

    // Enrollments
    Route::prefix('enrollments')->controller(TechEnrollmentController::class)->group(function () {
        Route::get('/', 'index')->name('teacher.enrollments.index');
        Route::post('/', 'store')->name('teacher.enrollments.store');
        Route::get('/edit/id={enroll}', 'edit')->name('teacher.enrollments.edit');
        Route::put('/id={enroll}', 'update')->name('teacher.enrollments.update');
        Route::delete('/id={enroll}', 'destroy')->name('teacher.enrollments.destroy');
        Route::delete('/course/id={course}', 'destroyCourse')->name('teacher.enrollments.destroyCourse');
    });

    // Exams
    Route::prefix('exams')->controller(TechExamController::class)->group(function () {
        Route::get('/', 'index')->name('teacher.exams.index');
        Route::get('/create', 'create')->name('teacher.exams.create');
        Route::post('/store', 'store')->name('teacher.exams.store');
        Route::get('/edit/id={exam}', 'edit')->name('teacher.exams.edit');
        Route::put('/id={exam}', 'update')->name('teacher.exams.update');
        Route::delete('/id={exam}', 'destroy')->name('teacher.exams.destroy');

        Route::get('/question-paper/id={exam}', 'questionPaper')->name('teacher.exams.questionPaper');
        Route::post('/question-paper/id={exam}/store', 'storeQuestion')->name('teacher.exams.questionPaper.store');
        Route::post('/id={exam}/question-paper/library', 'storeFromLibrary')->name('teacher.exams.questionPaper.library');
        Route::put('/question-paper/id={exam}/question/update/id={question}', 'updateQuestion')->name('teacher.exams.questionPaper.update');
        Route::delete('/question-paper/id={exam}/question/id={question}', 'destroyQuestion')->name('teacher.exams.questionPaper.destroy');

        Route::get('/settings/id={exam}', 'examSettings')->name('teacher.exams.settings');
        Route::put('/settings/id={exam}', 'updateExamSettings')->name('teacher.exams.settings.update');
    });

    // Exams Attempts
    Route::prefix('exam-attempts')->controller(TechExamAttemptController::class)->group(function () {
        Route::get('/', 'index')->name('teacher.examAttempts.index');
        Route::post('/id={attempt}/reset', 'reset')->name('teacher.examAttempts.reset');
    });

    // Questions
    Route::prefix('questions')->controller(TechQuestionController::class)->group(function () {
        Route::get('/', 'index')->name('teacher.questions.index');
        Route::get('/create', 'create')->name('teacher.questions.create');
        Route::post('/store', 'store')->name('teacher.questions.store');
        Route::get('/edit/id={question}', 'edit')->name('teacher.questions.edit');
        Route::put('/id={question}', 'update')->name('teacher.questions.update');
        Route::delete('/id={question}', 'destroy')->name('teacher.questions.destroy');
    });

    // Questions Library
    Route::prefix('questions-library')->controller(TechQuestionLibraryController::class)->group(function () {
        Route::get('/', 'index')->name('teacher.questions.library.index');
        Route::get('/create', 'create')->name('teacher.questions.library.create');
        Route::post('/store', 'store')->name('teacher.questions.library.store');
        Route::get('/edit/id={questionLib}', 'edit')->name('teacher.questions.library.edit');
        Route::put('/id={questionLib}', 'update')->name('teacher.questions.library.update');
        Route::delete('/id={questionLib}', 'destroy')->name('teacher.questions.library.destroy');
    });

    // AI Exam Sets
    Route::prefix('ai-exam-sets')->controller(TechExamSetController::class)->group(function () {
        Route::get('/', 'index')->name('teacher.aiExamSets.index');
        Route::get('/create', 'create')->name('teacher.aiExamSets.create');
        Route::post('/store', 'store')->name('teacher.aiExamSets.store');
        Route::get('/show/id={examSet}', 'show')->name('teacher.aiExamSets.show');
        Route::delete('/id={examSet}', 'destroy')->name('teacher.aiExamSets.destroy');

        Route::patch('/id={examSet}/status', 'updateStatus')->name('teacher.aiExamSets.status');
        Route::post('/id={examSet}/publish', 'publishToExam')->name('teacher.aiExamSets.publish');
        Route::post('/id={examSet}/update-marks', 'updateMarks')->name('teacher.aiExamSets.updateMarks');
    });

    // Review Answer
    Route::prefix('review-answer')->controller(TechReviewAnswerController::class)->group(function () {
        Route::get('/', 'index')->name('teacher.reviewAnswer.index');
        Route::get('/exam/id={exam}', 'show')->name('teacher.reviewAnswer.show');
        Route::get('/exam/id={exam}/student/id={student}', 'studentAnswers')->name('teacher.reviewAnswer.studentAnswers');
        Route::post('/store/exam/id={exam}/student/id={student}', 'storeReview')->name('teacher.reviewAnswer.store');
    });

    // Performance & Grading
    Route::prefix('performance')->controller(TechPerformanceController::class)->group(function () {
        Route::get('/', 'index')->name('teacher.performance.index');
        Route::get('/exam/id={exam}', 'examAnalytics')->name('teacher.performance.examAnalytics');
        Route::get('/exam/id={exam}/student/id={student}', 'studentReport')->name('teacher.performance.studentReport');
        Route::post('/store/exam/id={exam}/student/id={student}', 'storeReview')->name('teacher.performance.store');
    });

    // Proctoring Reports
    Route::prefix('proctoring')->controller(TechProctoringController::class)->group(function () {
        Route::get('/', 'index')->name('teacher.proctoring.index');
        Route::get('/report/id={attempt}', 'getReport')->name('teacher.proctoring.report');
        Route::get('/summary/id={attempt}', 'getSummary')->name('teacher.proctoring.summary');
    });

    // Teacher Profile
    Route::prefix('my-profile')->controller(TechProfileController::class)->group(function () {
        Route::get('/id={teacher}', 'myProfile')->name('teacher.myProfile');
        Route::post('/store/id={teacher}', 'myProfileStore')->name('teacher.myProfile.store');
    });

    // Teacher Misc
    Route::prefix('misc')->controller(TechMiscController::class)->group(function () {
        Route::get('/documentation', 'documentation')->name('teacher.misc.documentation');
        Route::get('/flowchart', 'flowchart')->name('teacher.misc.flowchart');
    });

});

require __DIR__.'/teacherauth.php';



// Student
Route::prefix('student')->middleware('auth:student')->group(function () {

    // Dashboard
    Route::prefix('dashboard')->controller(StudDashboardController::class)->group(function () {
        Route::get('/', 'dashboard')->name('student.dashboard');
    });

    // My Exams
    Route::prefix('my-exams')->controller(StudMyExamController::class)->group(function () {
        Route::get('/', 'index')->name('student.myExams');
        Route::get('/details/id={exam}', 'show')->name('student.myExams.show');
        Route::get('/answer-sheet/id={exam}', 'startExam')->name('student.myExams.start');
        Route::post('/store-answer/id={exam}', 'storeAnswer')->name('student.myExams.store');
        Route::get('/view-result/id={exam}', 'viewResult')->name('student.myExams.result');
        Route::get('/my-result/id={exam}', 'myResult')->name('student.myExams.myResult');
        Route::get('/rules/id={exam}', 'examRules')->name('student.myExams.rule');
    });

    // Proctoring
    Route::prefix('proctoring')->controller(StudProctoringController::class)->group(function () {
        Route::post('/tab-switch/id={attempt}',  'logTabSwitch')->name('student.proctoring.tabSwitch');
        Route::post('/clipboard/id={attempt}',   'logClipboard')->name('student.proctoring.clipboard');
        Route::post('/webcam/id={attempt}',      'logWebcam')->name('student.proctoring.webcam');
        Route::post('/event/id={attempt}',       'logEvent')->name('student.proctoring.event');
    });

    // My Results
    Route::prefix('my-results')->controller(StudMyResultController::class)->group(function () {
        Route::get('/', 'index')->name('student.myResults');
    });

    // Student Profile
    Route::prefix('my-profile')->controller(StudProfileController::class)->group(function () {
        Route::get('/id={student}', 'myProfile')->name('student.myProfile');
        Route::post('/store/id={student}', 'myProfileStore')->name('student.myProfile.store');
    });

    // Student Misc
    Route::prefix('misc')->controller(StudMiscController::class)->group(function () {
        Route::get('/documentation', 'documentation')->name('student.misc.documentation');
        Route::get('/flowchart', 'flowchart')->name('student.misc.flowchart');
    });

});

require __DIR__.'/studentauth.php';
