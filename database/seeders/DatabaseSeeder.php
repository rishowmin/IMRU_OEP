<?php

namespace Database\Seeders;

use Database\Seeders\AdminSeeder;
use Database\Seeders\CourseSeeder;
use Database\Seeders\ExamRulesSeeder;
use Database\Seeders\QuestionLibrarySeeder;
use Database\Seeders\StudentSeeder;
use Database\Seeders\TeacherSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            CourseSeeder::class,
            QuestionLibrarySeeder::class,
            ExamRulesSeeder::class
        ]);
    }
}
