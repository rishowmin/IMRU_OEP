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
        Schema::create('aca_exam_rules', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('instruction')->comment('rule, instruction');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->unsignedInteger('order')->default(0);

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
        Schema::dropIfExists('aca_exam_rules');
    }
};
