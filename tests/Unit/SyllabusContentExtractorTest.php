<?php

namespace Tests\Unit;

use App\Services\SyllabusContentExtractor;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use ZipArchive;

class SyllabusContentExtractorTest extends TestCase
{
    private array $temporaryFiles = [];

    protected function tearDown(): void
    {
        foreach ($this->temporaryFiles as $path) {
            if (is_file($path)) {
                unlink($path);
            }
        }

        parent::tearDown();
    }

    public function test_it_extracts_text_from_multiple_syllabus_formats(): void
    {
        $files = [
            UploadedFile::fake()->createWithContent('outline.txt', 'Text syllabus content'),
            $this->createZipUpload('outline.docx', [
                'word/document.xml' => '<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body><w:p><w:r><w:t>Word syllabus content</w:t></w:r></w:p></w:body></w:document>',
            ]),
            $this->createZipUpload('outline.pptx', [
                'ppt/slides/slide2.xml' => '<p:sld xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"><a:t>Second slide</a:t></p:sld>',
                'ppt/slides/slide1.xml' => '<p:sld xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"><a:t>First slide</a:t></p:sld>',
            ]),
            $this->createPdfUpload(),
        ];

        $result = (new SyllabusContentExtractor())->extract($files);

        $this->assertStringContainsString('Text syllabus content', $result);
        $this->assertStringContainsString('Word syllabus content', $result);
        $this->assertStringContainsString("First slide\nSecond slide", $result);
        $this->assertStringContainsString('PDF syllabus content', $result);
    }

    public function test_it_rejects_image_formats(): void
    {
        $image = UploadedFile::fake()->createWithContent('diagram.png', 'not-an-image');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('unsupported format');

        (new SyllabusContentExtractor())->extract([$image]);
    }

    public function test_it_converts_legacy_text_encodings_to_utf8(): void
    {
        $file = UploadedFile::fake()->createWithContent('legacy.txt', "Syllabus caf\xE9");

        $text = (new SyllabusContentExtractor())->extract([$file]);

        $this->assertTrue(mb_check_encoding($text, 'UTF-8'));
        $this->assertStringContainsString('Syllabus café', $text);
    }

    public function test_it_keeps_bounded_excerpts_instead_of_rejecting_long_files(): void
    {
        $longText = 'BEGIN ' . str_repeat('syllabus content ', 5000) . ' END';
        $file = UploadedFile::fake()->createWithContent('long-outline.txt', $longText);

        $text = (new SyllabusContentExtractor())->extract([$file]);

        $this->assertLessThanOrEqual(8192, strlen($text));
        $this->assertStringContainsString('BEGIN', $text);
        $this->assertStringContainsString('END', $text);
        $this->assertStringContainsString('middle omitted to fit AI request', $text);
    }

    public function test_it_includes_each_file_when_multiple_files_exceed_the_context_budget(): void
    {
        $files = [
            UploadedFile::fake()->createWithContent('first.txt', 'FIRST ' . str_repeat('first content ', 2000)),
            UploadedFile::fake()->createWithContent('second.txt', 'SECOND ' . str_repeat('second content ', 2000)),
        ];

        $text = (new SyllabusContentExtractor())->extract($files);

        $this->assertLessThanOrEqual(8192, strlen($text));
        $this->assertStringContainsString('Syllabus file: first.txt', $text);
        $this->assertStringContainsString('Syllabus file: second.txt', $text);
        $this->assertStringContainsString('FIRST', $text);
        $this->assertStringContainsString('SECOND', $text);
    }

    private function createZipUpload(string $name, array $entries): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'syllabus');
        $this->temporaryFiles[] = $path;

        $zip = new ZipArchive();
        $this->assertTrue($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE));
        foreach ($entries as $entry => $content) {
            $zip->addFromString($entry, $content);
        }
        $zip->close();

        return new UploadedFile($path, $name, 'application/zip', null, true);
    }

    private function createPdfUpload(): UploadedFile
    {
        $stream = "BT /F1 12 Tf 72 720 Td (PDF syllabus content) Tj ET\n";
        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            '<< /Length ' . strlen($stream) . " >>\nstream\n" . $stream . 'endstream',
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1) . " 0 obj\n{$object}\nendobj\n";
        }
        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 6\n0000000000 65535 f \n";
        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }
        $pdf .= "trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n{$xrefOffset}\n%%EOF\n";

        $path = tempnam(sys_get_temp_dir(), 'syllabus');
        $this->temporaryFiles[] = $path;
        file_put_contents($path, $pdf);

        return new UploadedFile($path, 'outline.pdf', 'application/pdf', null, true);
    }
}
