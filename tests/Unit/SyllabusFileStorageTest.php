<?php

namespace Tests\Unit;

use App\Services\SyllabusFileStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SyllabusFileStorageTest extends TestCase
{
    private string $originalPublicPath;
    private string $testPublicPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalPublicPath = app()->publicPath();
        $this->testPublicPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'syllabus-storage-' . uniqid();
        app()->instance('path.public', $this->testPublicPath);
    }

    protected function tearDown(): void
    {
        app()->instance('path.public', $this->originalPublicPath);
        File::deleteDirectory($this->testPublicPath);

        parent::tearDown();
    }

    public function test_it_saves_files_under_a_safe_exam_title_and_topic_folder(): void
    {
        $file = UploadedFile::fake()->createWithContent('Course Outline.txt', 'Syllabus text');
        $storage = new SyllabusFileStorage();

        $stored = $storage->store([$file], 'Midterm Exam', 'Computer Networks');

        $this->assertSame('Course Outline.txt', $stored[0]['name']);
        $this->assertStringStartsWith('midterm-exam-computer-networks/', $stored[0]['path']);
        $this->assertSame('Syllabus text', file_get_contents(
            public_path('assets/storage/syllabus/' . $stored[0]['path'])
        ));

        $storage->delete($stored);
        $this->assertFileDoesNotExist(public_path('assets/storage/syllabus/' . $stored[0]['path']));
    }

    public function test_it_returns_no_file_metadata_when_no_files_are_uploaded(): void
    {
        $this->assertSame([], (new SyllabusFileStorage())->store([], 'Exam', 'Topic'));
    }
}
