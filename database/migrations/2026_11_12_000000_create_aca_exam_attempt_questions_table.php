<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aca_exam_attempt_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('aca_exam_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('aca_questions')->cascadeOnDelete();
            $table->unsignedInteger('question_order');
            $table->timestamps();

            $table->unique(['attempt_id', 'question_id']);
            $table->unique(['attempt_id', 'question_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aca_exam_attempt_questions');
    }
};
