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
        Schema::create('aca_review_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_answers_id')->constrained('aca_exam_answers')->cascadeOnDelete();
            $table->boolean('review')->default(true)->comment('0=Wrong, 1=Correct');
            $table->decimal('marks_awarded', 8, 2)->default(0)->comment('Marks given by teacher');

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
        Schema::dropIfExists('aca_review_answers');
    }
};
