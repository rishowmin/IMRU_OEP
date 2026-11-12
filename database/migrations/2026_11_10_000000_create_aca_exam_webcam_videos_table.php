<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('aca_exam_webcam_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->unique()->constrained('aca_exam_attempts')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('mime_type');
            $table->unsignedInteger('chunk_count')->default(0);
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aca_exam_webcam_videos');
    }
};
