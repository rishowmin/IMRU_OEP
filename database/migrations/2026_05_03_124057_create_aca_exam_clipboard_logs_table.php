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
        Schema::create('aca_exam_clipboard_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('aca_exam_attempts')->cascadeOnDelete();
            $table->string('action_type')->comment('copy, paste, cut');
            $table->timestamp('attempted_at');

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
        Schema::dropIfExists('aca_exam_clipboard_logs');
    }
};
