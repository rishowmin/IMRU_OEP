<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser;
use ZipArchive;

class SyllabusContentExtractor
{
    private const MAX_CONTEXT_BYTES = 8192;
    private const MAX_ARCHIVE_XML_SIZE = 6000000;

    /**
     * @param  array<int, UploadedFile>  $files
     * @return string
     */
    public function extract(array $files): string
    {
        $documents = [];

        foreach ($files as $file) {
            $extension = strtolower($file->getClientOriginalExtension());
            $name = mb_substr($this->normalizeUtf8(basename($file->getClientOriginalName())), 0, 150);

            $content = match ($extension) {
                'pdf' => $this->extractPdf($file),
                'docx' => $this->extractOfficeText($file, 'word/document.xml', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'),
                'pptx' => $this->extractPresentationText($file),
                'txt' => $this->extractPlainText($file),
                default => throw new \RuntimeException("The file \"{$name}\" has an unsupported format."),
            };

            $content = trim($this->normalizeUtf8($content));
            if ($content === '') {
                throw new \RuntimeException("No readable text was found in \"{$name}\". Scanned PDFs are not supported; upload a text-based PDF instead.");
            }

            $documents[] = [
                'header' => "Syllabus file: {$name}\n",
                'content' => $content,
            ];
        }

        if (!$documents) {
            return '';
        }

        $headerBytes = array_sum(array_map(fn ($document) => strlen($document['header']), $documents));
        $separatorBytes = 2 * (count($documents) - 1);
        $contentBudget = max(0, self::MAX_CONTEXT_BYTES - $headerBytes - $separatorBytes);
        $perDocumentBudget = intdiv($contentBudget, count($documents));

        $formatted = array_map(function ($document) use ($perDocumentBudget) {
            return $document['header'] . $this->excerpt($document['content'], $perDocumentBudget);
        }, $documents);

        return implode("\n\n", $formatted);
    }

    private function excerpt(string $text, int $byteLimit): string
    {
        if (strlen($text) <= $byteLimit) {
            return $text;
        }

        $marker = "\n[... middle omitted to fit AI request ...]\n";
        $availableBytes = $byteLimit - strlen($marker);
        if ($availableBytes <= 0) {
            return mb_strcut($text, 0, $byteLimit, 'UTF-8');
        }

        $headBytes = intdiv($availableBytes, 2);
        $tailBytes = $availableBytes - $headBytes;
        $head = mb_strcut($text, 0, $headBytes, 'UTF-8');
        $tail = mb_strcut($text, -$tailBytes, null, 'UTF-8');

        return $head . $marker . $tail;
    }

    private function extractPdf(UploadedFile $file): string
    {
        try {
            return (new Parser())->parseFile($file->getRealPath())->getText();
        } catch (\Throwable $e) {
            throw new \RuntimeException('A PDF file could not be read. Please upload a valid, unencrypted PDF.', 0, $e);
        }
    }

    private function extractPlainText(UploadedFile $file): string
    {
        $text = file_get_contents($file->getRealPath());
        if ($text === false) {
            throw new \RuntimeException('A text file could not be read.');
        }

        if (substr($text, 0, 2) === "\xFF\xFE") {
            $text = mb_convert_encoding(substr($text, 2), 'UTF-8', 'UTF-16LE');
        } elseif (substr($text, 0, 2) === "\xFE\xFF") {
            $text = mb_convert_encoding(substr($text, 2), 'UTF-8', 'UTF-16BE');
        } elseif (substr($text, 0, 3) === "\xEF\xBB\xBF") {
            $text = substr($text, 3);
        } elseif (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'Windows-1252');
        }

        return $this->normalizeUtf8($text);
    }

    private function normalizeUtf8(string $text): string
    {
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        }

        if (!mb_check_encoding($text, 'UTF-8')) {
            throw new \RuntimeException('A syllabus file contains text that could not be converted to UTF-8.');
        }

        return $text;
    }

    private function extractPresentationText(UploadedFile $file): string
    {
        $zip = $this->openOfficeArchive($file);

        try {
            $slideNames = [];
            $xmlSize = 0;
            for ($index = 0; $index < $zip->numFiles; $index++) {
                $name = $zip->getNameIndex($index);
                if (preg_match('#^ppt/slides/slide\d+\.xml$#', $name)) {
                    $stat = $zip->statIndex($index);
                    if ($stat === false) {
                        throw new \RuntimeException('A PowerPoint slide could not be inspected.');
                    }
                    $xmlSize += $stat['size'];
                    if ($xmlSize > self::MAX_ARCHIVE_XML_SIZE) {
                        throw new \RuntimeException('The PowerPoint file contains too much text to process.');
                    }
                    $slideNames[] = $name;
                }
            }

            natsort($slideNames);
            $slides = [];
            foreach ($slideNames as $name) {
                $slides[] = $this->extractXmlText($this->readArchiveXml($zip, $name), 'http://schemas.openxmlformats.org/drawingml/2006/main');
            }

            if (!$slides) {
                throw new \RuntimeException('The PowerPoint file does not contain readable slides.');
            }

            return implode("\n", $slides);
        } finally {
            $zip->close();
        }
    }

    private function extractOfficeText(UploadedFile $file, string $part, string $namespace): string
    {
        $zip = $this->openOfficeArchive($file);

        try {
            return $this->extractXmlText($this->readArchiveXml($zip, $part), $namespace);
        } finally {
            $zip->close();
        }
    }

    private function openOfficeArchive(UploadedFile $file): ZipArchive
    {
        $zip = new ZipArchive();
        if ($zip->open($file->getRealPath()) !== true) {
            throw new \RuntimeException('A DOCX or PPTX file could not be read. Please upload a valid Office document.');
        }

        return $zip;
    }

    private function readArchiveXml(ZipArchive $zip, string $name): string
    {
        $index = $zip->locateName($name);
        $stat = $index === false ? false : $zip->statIndex($index);

        if ($stat === false || $stat['size'] > self::MAX_ARCHIVE_XML_SIZE) {
            throw new \RuntimeException('An Office document contains a missing or oversized text section.');
        }

        $xml = $zip->getFromIndex($index);
        if ($xml === false) {
            throw new \RuntimeException('An Office document text section could not be read.');
        }

        return $xml;
    }

    private function extractXmlText(string $xml, string $namespace): string
    {
        if (stripos($xml, '<!DOCTYPE') !== false) {
            throw new \RuntimeException('An Office document contains invalid XML.');
        }

        $document = new \DOMDocument();
        $previousErrorMode = libxml_use_internal_errors(true);
        try {
            if (!$document->loadXML($xml, LIBXML_NONET | LIBXML_COMPACT)) {
                throw new \RuntimeException('An Office document contains invalid XML.');
            }
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousErrorMode);
        }

        $text = [];
        foreach ($document->getElementsByTagNameNS($namespace, 't') as $node) {
            $text[] = $node->textContent;
        }

        return implode(' ', $text);
    }
}
