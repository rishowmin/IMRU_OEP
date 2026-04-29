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
        Schema::create('aca_courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_title');
            $table->string('course_code')->unique()->nullable();
            $table->string('credits')->nullable();
            $table->longText('description')->nullable();

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
        Schema::dropIfExists('aca_courses');
    }
};
