<?php

namespace Database\Seeders;

use App\Models\Academic\AcaCourse;
use Illuminate\Database\Seeder;

class AcaCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = [
            // Batch 27 = 31 entry
            ['course_title' => 'ICT',                                  'course_code' => 'ICT-1234',        'credits' => '3'],
            ['course_title' => 'Cryptography and Steganography',       'course_code' => 'PMIT-6204',       'credits' => '3'],
            ['course_title' => 'UI and UX',                            'course_code' => 'PMIT-6224',       'credits' => '3'],
            ['course_title' => 'IoT and Fog Computing',                'course_code' => 'PMIT-6223',       'credits' => '3'],
            ['course_title' => 'Human Computer Interaction',           'course_code' => 'PMIT-6311',       'credits' => '3'],
        ];

        foreach ($courses as $course) {
            AcaCourse::create([
                'course_title' => $course['course_title'],
                'course_code'  => $course['course_code'],
                'credits'      => $course['credits'],
                'is_active'    => 1,
            ]);
        }
    }
}
