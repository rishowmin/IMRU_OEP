<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = [
            // Batch 27 = 31 entry
            ['first_name' => 'Md. Tanvir',               'last_name' => 'Hossain',       'email' => '223201@imru.com'],
            ['first_name' => 'Md.',                      'last_name' => 'Mohshin',       'email' => '223202@imru.com'],
            ['first_name' => 'Goutam',                   'last_name' => 'Biswas',        'email' => '223203@imru.com'],
            ['first_name' => 'Dulal Kumar',              'last_name' => 'Gomasta',       'email' => '223204@imru.com'],
            ['first_name' => 'Tashfiq',                  'last_name' => 'Ahmed',         'email' => '223205@imru.com'],
            ['first_name' => 'Mahmudur',                 'last_name' => 'Rahman',        'email' => '223206@imru.com'],
            ['first_name' => 'Aziza Sarker',             'last_name' => 'Rimie',         'email' => '223207@imru.com'],
            ['first_name' => 'Md. Habibur Rahman',       'last_name' => 'Papel',         'email' => '223208@imru.com'],
            ['first_name' => 'Md. Shadman',              'last_name' => 'Sakib',         'email' => '223209@imru.com'],
            ['first_name' => 'Md. Shadman',              'last_name' => 'Saeed',         'email' => '223210@imru.com'],
            ['first_name' => 'Fahim',                    'last_name' => 'Iftekhar',      'email' => '223211@imru.com'],
            ['first_name' => 'Md. Shefatul',             'last_name' => 'Islam',         'email' => '223212@imru.com'],
            ['first_name' => 'Mehnaz Binte',             'last_name' => 'Zia',           'email' => '223213@imru.com'],
            ['first_name' => 'Mokarromah',               'last_name' => 'Akter',         'email' => '223214@imru.com'],
            ['first_name' => 'Anik',                     'last_name' => 'Das',           'email' => '223215@imru.com'],
            ['first_name' => 'Md. Belawal Hoque',        'last_name' => 'Adib',          'email' => '223216@imru.com'],
            ['first_name' => 'Rakibul Hasan',            'last_name' => 'Patwary',       'email' => '223217@imru.com'],
            ['first_name' => 'Jaka',                     'last_name' => 'Soran',         'email' => '223218@imru.com'],
            ['first_name' => 'Mir Sabbir Rahman',        'last_name' => 'Ridoy',         'email' => '223219@imru.com'],
            ['first_name' => 'Md. Zehad Hasan',          'last_name' => 'Maruf',         'email' => '223220@imru.com'],
            ['first_name' => 'Sharmin Akter',            'last_name' => 'Kanta',         'email' => '223221@imru.com'],
            ['first_name' => 'Kaniz',                    'last_name' => 'Sultana',       'email' => '223222@imru.com'],
            ['first_name' => 'Iskedaheer',               'last_name' => 'Alam',          'email' => '223223@imru.com'],
            ['first_name' => 'Mithun',                   'last_name' => 'Acharjee',      'email' => '223224@imru.com'],
            ['first_name' => 'Hafizul Islam',            'last_name' => 'Khan',          'email' => '223225@imru.com'],
            ['first_name' => 'Md. Abdullah Al',          'last_name' => 'Shahriar',      'email' => '223226@imru.com'],
            ['first_name' => 'Md. Anik',                 'last_name' => 'Kamal',         'email' => '223227@imru.com'],
            ['first_name' => 'Md. Mahabubur',            'last_name' => 'Rahman',        'email' => '223228@imru.com'],
            ['first_name' => 'Md. Belal',                'last_name' => 'Hossain',       'email' => '223229@imru.com'],
            ['first_name' => 'Shuva',                    'last_name' => 'Barua',         'email' => '223230@imru.com'],
            ['first_name' => 'Md. Nahidul',              'last_name' => 'Islam',         'email' => '223231@imru.com'],

            // Batch 33 = 1 entry
            ['first_name' => 'Md. Sabbir',               'last_name' => 'Ahamed',    'email' => '243023@imru.com'],

            // Batch 34 = 68 entry
            ['first_name' => 'Md. Bayzid Hasan',         'last_name' => 'Bhuyan',    'email' => '251001@imru.com'],
            ['first_name' => 'Md. Monir ',               'last_name' => 'Hossain', 'email' => '251002@imru.com'],
            ['first_name' => 'Tasmima',                  'last_name' => 'Haque',     'email' => '251003@imru.com'],
            ['first_name' => 'Md. Rafatuzzaman',         'last_name' => 'Khan',      'email' => '251004@imru.com'],
            ['first_name' => 'Sahriar Hossen',           'last_name' => 'Imran',     'email' => '251005@imru.com'],
            ['first_name' => 'Md. Wasik',                'last_name' => 'Billah',    'email' => '251006@imru.com'],
            ['first_name' => 'Shirina',                  'last_name' => 'Khatun',    'email' => '251007@imru.com'],
            ['first_name' => 'Rokibul',                  'last_name' => 'Hasan',     'email' => '251008@imru.com'],
            ['first_name' => 'Md. Salim',                'last_name' => 'Uddin',     'email' => '251009@imru.com'],
            ['first_name' => 'Minhazur',                 'last_name' => 'Rahaman',   'email' => '251010@imru.com'],
            ['first_name' => 'Md. Mirajul Islam',        'last_name' => 'Tashfi',    'email' => '251011@imru.com'],
            ['first_name' => 'Anoy',                     'last_name' => 'Podder',    'email' => '251012@imru.com'],
            ['first_name' => 'Md. Kamrul',               'last_name' => 'Islam',     'email' => '251013@imru.com'],
            ['first_name' => 'Rahmat',                   'last_name' => 'Ullah',     'email' => '251014@imru.com'],
            ['first_name' => 'Sinthia',                  'last_name' => 'Mamtaz',    'email' => '251015@imru.com'],
            ['first_name' => 'Mobasshir',                'last_name' => 'Kaisar',    'email' => '251016@imru.com'],
            ['first_name' => 'Tahsin',                   'last_name' => 'Alam',      'email' => '251017@imru.com'],
            ['first_name' => 'Amio',                     'last_name' => 'Ghosh',     'email' => '251018@imru.com'],
            ['first_name' => 'Md. Zubaidur Rahman',      'last_name' => 'Bagmar',    'email' => '251019@imru.com'],
            ['first_name' => 'Md. Jahidul Islam',        'last_name' => 'Maruf',     'email' => '251020@imru.com'],
            ['first_name' => 'K M Abdulla Al',           'last_name' => 'Mamun',     'email' => '251021@imru.com'],
            ['first_name' => 'Kazi Rezaul',              'last_name' => 'Karim',     'email' => '251022@imru.com'],
            ['first_name' => 'Muhammad Raisul',          'last_name' => 'Islam',     'email' => '251023@imru.com'],
            ['first_name' => 'Piash Kumar',              'last_name' => 'Das',       'email' => '251024@imru.com'],
            ['first_name' => 'Minhaz',                   'last_name' => 'Uddin',     'email' => '251025@imru.com'],
            ['first_name' => 'Rounok Jahan',             'last_name' => 'Priya',     'email' => '251026@imru.com'],
            ['first_name' => 'Al-Amin Muhammad Murtaja', 'last_name' => 'Ullah',     'email' => '251027@imru.com'],
            ['first_name' => 'Mashaba',                  'last_name' => 'Nawrin',    'email' => '251028@imru.com'],
            ['first_name' => 'Md. Alvi',                 'last_name' => 'Nirob',     'email' => '251029@imru.com'],
            ['first_name' => 'Md. Amanullah',            'last_name' => 'Rafi',      'email' => '251030@imru.com'],
            ['first_name' => 'Zarin',                    'last_name' => 'Akter',     'email' => '251031@imru.com'],
            ['first_name' => 'Tanvir',                   'last_name' => 'Rahman',    'email' => '251032@imru.com'],
            ['first_name' => 'Rafi Al',                  'last_name' => 'Adnan',     'email' => '251033@imru.com'],
            ['first_name' => 'Md. Imam',                 'last_name' => 'Hosain',    'email' => '251034@imru.com'],
            ['first_name' => 'Farid',                    'last_name' => 'Ahmed',     'email' => '251035@imru.com'],
            ['first_name' => 'Md. Arosh',                'last_name' => 'Prodhen',   'email' => '251036@imru.com'],
            ['first_name' => 'S. M. Shahidul',           'last_name' => 'Alam',      'email' => '251037@imru.com'],
            ['first_name' => 'Md. Monir',                'last_name' => 'Alam',      'email' => '251038@imru.com'],
            ['first_name' => 'Abu Naiim Md. Rayhan',     'last_name' => 'Siddique',  'email' => '251039@imru.com'],
            ['first_name' => 'Mashiur',                  'last_name' => 'Rahman',    'email' => '251040@imru.com'],
            ['first_name' => 'Md. Rakibul',              'last_name' => 'Islam',     'email' => '251041@imru.com'],
            ['first_name' => 'Farjana Akther',           'last_name' => 'Hima',      'email' => '251042@imru.com'],
            ['first_name' => 'Md. Muhaimin Islam',       'last_name' => 'Tanvir',    'email' => '251043@imru.com'],
            ['first_name' => 'Zafrin',                   'last_name' => 'Chowdhury', 'email' => '251044@imru.com'],
            ['first_name' => 'Md. Tuhinur Rahman',       'last_name' => 'Tuhin',     'email' => '251045@imru.com'],
            ['first_name' => 'Mahmudul Hassan',          'last_name' => 'Shihab',    'email' => '251046@imru.com'],
            ['first_name' => 'Mahmudur',                 'last_name' => 'Rahman',    'email' => '251047@imru.com'],
            ['first_name' => 'Hosne Ara',                'last_name' => 'Bithi',     'email' => '251048@imru.com'],
            ['first_name' => 'Razown Ahamed',            'last_name' => 'Sovuz',     'email' => '251049@imru.com'],
            ['first_name' => 'Md. Masum',                'last_name' => 'Pramanik',  'email' => '251050@imru.com'],
            ['first_name' => 'Amir',                     'last_name' => 'Hossain',   'email' => '251051@imru.com'],
            ['first_name' => 'Fouzia Akter',             'last_name' => 'Rifa',      'email' => '251052@imru.com'],
            ['first_name' => 'M. Neehal',                'last_name' => 'Sharif',    'email' => '251053@imru.com'],
            ['first_name' => 'Md. Shaon',                'last_name' => 'Khalifa',   'email' => '251054@imru.com'],
            ['first_name' => 'Romjan',                   'last_name' => 'Ali',       'email' => '251055@imru.com'],
            ['first_name' => 'Ratul',                    'last_name' => 'Biswas',    'email' => '251056@imru.com'],
            ['first_name' => 'S. M. Sohanur',            'last_name' => 'Khan',      'email' => '251057@imru.com'],
            ['first_name' => 'Md. Alimul Islam',         'last_name' => 'Imon',      'email' => '251058@imru.com'],
            ['first_name' => 'Arpita',                   'last_name' => 'Basak',     'email' => '251059@imru.com'],
            ['first_name' => 'Md. Tariqul',              'last_name' => 'Islam',     'email' => '251060@imru.com'],
            ['first_name' => 'Abdullah Al',              'last_name' => 'Afraaz',    'email' => '251061@imru.com'],
            ['first_name' => 'Sadia',                    'last_name' => 'Islam',     'email' => '251062@imru.com'],
            ['first_name' => 'Aysha',                    'last_name' => 'Siddeka',   'email' => '251063@imru.com'],
            ['first_name' => 'Rakibul',                  'last_name' => 'Hasib',     'email' => '251064@imru.com'],
            ['first_name' => 'Fahim',                    'last_name' => 'Ahmed',     'email' => '251065@imru.com'],
            ['first_name' => 'S.M. Ferdous',             'last_name' => 'Azad',      'email' => '251066@imru.com'],
            ['first_name' => 'Muhammad',                 'last_name' => 'Mahdi',     'email' => '251067@imru.com'],
            ['first_name' => 'Nilufa',                   'last_name' => 'Yesmin',    'email' => '251068@imru.com'],
        ];

        foreach ($students as $student) {
            Student::create([
                'first_name' => $student['first_name'],
                'last_name'  => $student['last_name'],
                'email'      => $student['email'],
                'password'   => Hash::make('12345678'),
                'is_active'  => 1,
            ]);
        }
    }
}
