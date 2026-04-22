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
        Schema::create('aca_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('aca_exams')->cascadeOnDelete();

            $table->string('question_type');
            $table->text('question_text');
            $table->string('difficulty_level')->default('medium')->comment('easy, medium, hard');
            $table->unsignedDecimal('marks', 5, 2)->default(1.00);
            $table->string('evaluation_type')->default('automatic')->comment('automatic=objective, manual=subjective');
            $table->string('option_a')->nullable();
            $table->string('option_b')->nullable();
            $table->string('option_c')->nullable();
            $table->string('option_d')->nullable();
            $table->longText('correct_answer')->nullable();
            $table->string('question_figure')->nullable();
            $table->unsignedInteger('question_order')->default(0);

            $table->boolean('is_active')->default(true)->comment('0=Deactive, 1=Active');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aca_questions');
    }
};
