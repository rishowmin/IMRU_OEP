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
        Schema::create('aca_exam_webcam_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('aca_exam_attempts')->cascadeOnDelete();
            $table->string('image_url')->nullable()->comment('Stored in S3/cloud');
            $table->string('ai_flag')->default('clear')->comment('clear, no_face, multiple_faces, suspicious');
            $table->decimal('confidence', 5, 2)->nullable()->comment('AI confidence score 0-100');
            $table->timestamp('captured_at');

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
        Schema::dropIfExists('aca_exam_webcam_logs');
    }
};
