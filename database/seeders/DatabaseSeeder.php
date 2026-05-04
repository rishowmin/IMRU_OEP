<?php

namespace Database\Seeders;

use Database\Seeders\AcaCourseSeeder;
use Database\Seeders\AcaQuestionLibrarySeeder;
use Database\Seeders\AcaExamRulesSeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\StudentSeeder;
use Database\Seeders\TeacherSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            AcaCourseSeeder::class,
            AcaQuestionLibrarySeeder::class,
            AcaExamRulesSeeder::class
        ]);
    }
}
