<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyllabusFileStorage
{
    public const STORAGE_DIRECTORY = 'assets/storage/syllabus';

    /**
     * @param  array<int, UploadedFile>  $files
     * @return array<int, array{name: string, path: string}>
     */
    public function store(array $files, string $title, string $topic): array
    {
        if (!$files) {
            return [];
        }

        $folder = Str::slug($title . '-' . $topic) ?: 'exam-set';
        $directory = public_path(self::STORAGE_DIRECTORY . DIRECTORY_SEPARATOR . $folder);
        File::ensureDirectoryExists($directory);

        $storedFiles = [];

        try {
            foreach ($files as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = strtolower($file->getClientOriginalExtension());
                $baseName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) ?: 'syllabus';
                $storedName = $baseName . '-' . Str::random(12) . '.' . $extension;

                $file->move($directory, $storedName);
                $storedFiles[] = [
                    'name' => $originalName,
                    'path' => $folder . '/' . $storedName,
                ];
            }
        } catch (\Throwable $e) {
            $this->delete($storedFiles);
            throw new \RuntimeException('Unable to save the uploaded syllabus files. Please try again.', 0, $e);
        }

        return $storedFiles;
    }

    /**
     * @param  array<int, array{name?: string, path?: string}>  $files
     */
    public function delete(array $files): void
    {
        $baseDirectory = realpath(public_path(self::STORAGE_DIRECTORY));
        if ($baseDirectory === false) {
            return;
        }

        foreach ($files as $file) {
            $relativePath = $file['path'] ?? '';
            if (!is_string($relativePath) || !preg_match('#^[a-z0-9-]+/[A-Za-z0-9._-]+$#', $relativePath)) {
                continue;
            }

            $path = public_path(self::STORAGE_DIRECTORY . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath));
            $resolvedPath = realpath($path);
            if ($resolvedPath === false || !str_starts_with($resolvedPath, $baseDirectory . DIRECTORY_SEPARATOR)) {
                continue;
            }

            if (is_file($resolvedPath) && !File::delete($resolvedPath)) {
                Log::warning('[Syllabus] Failed to remove stored upload during cleanup', ['path' => $relativePath]);
            }
        }
    }
}
