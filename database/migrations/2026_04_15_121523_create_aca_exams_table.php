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
        Schema::create('aca_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('aca_courses')->cascadeOnDelete();
            $table->string('exam_title')->nullable();
            $table->string('exam_code')->unique()->nullable();
            $table->string('exam_type')->nullable();
            $table->dateTime('exam_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('exam_duration_min')->nullable();
            $table->decimal('total_marks', 8, 2)->nullable();
            $table->decimal('passing_marks', 8, 2)->nullable();
            $table->integer('total_questions')->nullable();
            $table->longText('instructions')->nullable();
            $table->longText('basic_rules')->nullable();

            $table->boolean('is_active')->default(true)->comment('0=Deactive, 1=Active');

            $table->string('aca_created_by')->nullable();
            $table->string('aca_updated_by')->nullable();
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
        Schema::dropIfExists('aca_exams');
    }
};
