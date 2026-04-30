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
        Schema::create('teacher_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();

            // Academic
            $table->string('teacher_id_no')->nullable()->unique()->comment('Academic teacher ID');
            $table->string('designation')->nullable()->comment('e.g. Professor, Associate Professor, Lecturer');
            $table->string('department')->nullable();
            $table->string('specialization')->nullable()->comment('e.g. Machine Learning, Data Science');
            $table->string('qualification')->nullable()->comment('e.g. PhD, MSc, BSc');
            $table->string('joining_date')->nullable();
            $table->string('experience_years')->nullable();

            // Personal
            $table->string('gender')->nullable()->comment('male, female, other');
            $table->date('dob')->nullable();
            $table->string('blood_group')->nullable()->comment('A+, A-, B+, B-, AB+, AB-, O+, O-');
            $table->string('religion')->nullable();
            $table->string('nationality')->nullable()->default('Bangladeshi');
            $table->string('marital_status')->nullable()->comment('single, married, divorced, widowed');
            $table->string('nid_number')->nullable()->unique();
            $table->string('birth_certificate_no')->nullable();

            // Contact
            $table->string('phone')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relation')->nullable();

            // Address
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('division')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable()->default('Bangladesh');

            // Profile
            $table->string('profile_photo')->nullable();
            $table->longText('bio')->nullable();

            // Social
            $table->string('linkedin')->nullable();
            $table->string('google_scholar')->nullable();
            $table->string('researchgate')->nullable();
            $table->string('website')->nullable();

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
        Schema::dropIfExists('teacher_infos');
    }
};
