<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teachers = [
            ['first_name' => 'Dr. Shamim Al',       'last_name' => 'Mamun',       'email' => 'shamim@juniv.edu'],
            ['first_name' => 'Dr. M. Shamim',       'last_name' => 'Kaiser',      'email' => 'mskaiser@juniv.edu'],
            ['first_name' => 'Dr. Risala Tasin',    'last_name' => 'Khan',        'email' => 'risala@juniv.edu'],
            ['first_name' => 'K M Akkas',           'last_name' => 'Ali',         'email' => 'akkas@juniv.edu'],
            ['first_name' => 'Md. Fazlul Karim',    'last_name' => 'Patwary',     'email' => 'patwary@juniv.edu'],
        ];

        foreach ($teachers as $teacher) {
            Teacher::create([
                'first_name' => $teacher['first_name'],
                'last_name'  => $teacher['last_name'],
                'email'      => $teacher['email'],
                'password'   => Hash::make('12345678'),
                'is_active'  => 1,
            ]);
        }
    }
}
