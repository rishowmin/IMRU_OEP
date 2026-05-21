<?php

namespace Database\Seeders;

use App\Models\Academic\AcaExamRule;
use Illuminate\Database\Seeder;

class AcaExamRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rules = [
            // Instructions
            ['type' => 'instruction', 'key' => 'timer_policy',       'title' => 'Timer Policy',        'description' => 'The timer will start as soon as you click Start Exam. You cannot pause it.',                                               'order' => 1],
            ['type' => 'instruction', 'key' => 'auto_submit',        'title' => 'Auto Submit',          'description' => 'Submit your answers before the timer runs out. The exam will be auto-submitted when time expires.',                         'order' => 2],
            ['type' => 'instruction', 'key' => 'single_attempt',     'title' => 'Single Attempt',       'description' => 'You can only attempt this exam once. Re-entry is not allowed after submission.',                                           'order' => 3],
            ['type' => 'instruction', 'key' => 'internet_connection','title' => 'Internet Connection',  'description' => 'Ensure a stable internet connection before starting the exam.',                                                             'order' => 4],

            // Rules
            ['type' => 'rule', 'key' => 'tab_switching',           'title' => 'Tab Switching',            'description' => 'Do not switch tabs, minimize or restore the browser, or open any other browser tab during the exam. It will count as a violation.',                                 'order' => 1],
            ['type' => 'rule', 'key' => 'fullscreen_required',     'title' => 'Fullscreen Required',      'description' => 'During the exam, your browser must remain in fullscreen mode at all times. Exiting fullscreen will be recorded as a violation and reported to your instructor.',    'order' => 3],
            ['type' => 'rule', 'key' => 'webcam_required',         'title' => 'Webcam Required',          'description' => 'You must have a working webcam connected and enabled before starting the exam. If you do not give access, it will count as a violation',                            'order' => 3],
        ];

        foreach ($rules as $rule) {
            AcaExamRule::updateOrCreate(
                ['key' => $rule['key']],
                [
                    'type'        => $rule['type'],
                    'title'       => $rule['title'],
                    'description' => $rule['description'],
                    'order'       => $rule['order'],
                    'is_active'   => true,
                ]
            );
        }
    }
}
