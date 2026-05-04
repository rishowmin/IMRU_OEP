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
        Schema::create('aca_exam_proctoring_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('aca_exam_attempts')->cascadeOnDelete();
            $table->string('event_type')->comment('tab_switch, copy_attempt, paste_attempt, face_not_detected, multiple_faces, looking_away');
            $table->string('severity')->default('low')->comment('low, medium, high');
            $table->json('metadata')->nullable()->comment('Extra AI details');
            $table->timestamp('detected_at');

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
        Schema::dropIfExists('aca_exam_proctoring_events');
    }
};
