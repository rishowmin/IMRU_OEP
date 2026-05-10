<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aca_exam_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained('aca_exams')->cascadeOnDelete();

            // MCQ (auto-graded)
            $table->integer('mcq_total')->default(0)->comment('Total MCQ questions attempted');
            $table->integer('mcq_correct')->default(0)->comment('Correct MCQ answers');
            $table->integer('mcq_wrong')->default(0)->comment('Wrong MCQ answers');
            $table->integer('mcq_unanswered')->default(0)->comment('MCQ questions not answered');
            $table->decimal('mcq_marks_obtained', 8, 2)->default(0)->comment('Marks from MCQ');
            $table->decimal('mcq_total_marks', 8, 2)->default(0)->comment('Total possible MCQ marks');

            // Subjective (manually reviewed)
            $table->integer('subjective_total')->default(0)->comment('Total subjective questions');
            $table->integer('subjective_reviewed')->default(0)->comment('How many have been reviewed');
            $table->decimal('subjective_marks_obtained', 8, 2)->default(0)->comment('Marks from subjective');
            $table->decimal('subjective_total_marks', 8, 2)->default(0)->comment('Total possible subjective marks');

            // Grand totals
            $table->decimal('total_marks_obtained', 8, 2)->default(0);
            $table->decimal('total_marks', 8, 2)->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->string('grade')->nullable()->comment('A+, A, A-, B+, B, B-, C+, C, D, F');
            $table->boolean('is_pass')->default(false);

            // Status
            $table->enum('grading_status', ['pending', 'partial', 'complete'])
                  ->default('pending')
                  ->comment('pending=not graded, partial=subjective pending, complete=fully graded');

            $table->timestamp('graded_at')->nullable();
            $table->boolean('is_active')->default(true);

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['student_id', 'exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aca_exam_results');
    }
};
