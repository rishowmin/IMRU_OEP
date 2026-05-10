<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aca_exam_sets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('topic')->default('General');
            $table->string('question_type')->default('All')->comment('e.g. all, objective, subjective, mcq_4, mcq_2, short_question, long_question');
            $table->unsignedInteger('total_questions');
            $table->unsignedInteger('easy_count')->default(0);
            $table->unsignedInteger('medium_count')->default(0);
            $table->unsignedInteger('hard_count')->default(0);
            $table->unsignedInteger('duration_minutes')->default(60);
            $table->unsignedInteger('total_marks')->default(0);

            // AI reasoning stored for transparency/audit
            $table->text('ai_reasoning')->nullable()
                  ->comment('Gemini\'s explanation of how it selected/balanced questions');

            // The selected question IDs as JSON array
            $table->json('question_ids')
                  ->comment('Ordered list of selected question IDs');
            $table->json('custom_marks')->nullable()
                  ->comment('Custom Marks for Each Questions');

            // Randomization seed per candidate (for reproducibility)
            $table->string('randomization_seed')->nullable();

            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');

            $table->unsignedBigInteger('published_exam_id')->nullable()
                  ->comment('aca_exams.id created when this set was published');

            $table->string('aca_created_by')->nullable();
            $table->string('aca_updated_by')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aca_exam_sets');
    }
};
