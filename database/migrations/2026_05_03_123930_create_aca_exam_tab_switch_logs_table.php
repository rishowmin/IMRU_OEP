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
        Schema::create('aca_exam_tab_switch_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('aca_exam_attempts')->cascadeOnDelete();
            $table->timestamp('switched_at');
            $table->timestamp('returned_at')->nullable();
            $table->integer('duration_ms')->nullable()->comment('Time away in milliseconds');
            $table->integer('switch_count')->default(0)->comment('Cumulative switches in session');

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
        Schema::dropIfExists('aca_exam_tab_switch_logs');
    }
};
