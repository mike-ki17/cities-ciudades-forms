<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload a file and return file information
     */
    public function uploadFile(UploadedFile $file, string $formId, string $fieldKey, array $validations = []): array
    {
        // Validate file
        $this->validateFile($file, $validations);

        // Generate unique filename
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension;
        
        // Create directory path
        $directory = "forms/{$formId}/{$fieldKey}/" . date('Y/m/d');
        $filePath = $directory . '/' . $filename;

        // Determine storage disk
        $disk = $this->getStorageDisk();

        // Store file
        $storedPath = $file->storeAs($directory, $filename, $disk);

        // Get file URL
        $fileUrl = $this->getFileUrl($storedPath, $disk);

        // Return file information
        return [
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $filename,
            'path' => $storedPath,
            'url' => $fileUrl,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $extension,
            'uploaded_at' => now()->toISOString(),
            'disk' => $disk,
        ];
    }

    /**
     * Validate file against field validations
     */
    private function validateFile(UploadedFile $file, array $validations): void
    {
        // Check file types
        if (isset($validations['file_types']) && is_array($validations['file_types'])) {
            $allowedTypes = $validations['file_types'];
            $fileExtension = strtolower($file->getClientOriginalExtension());
            
            if (!in_array($fileExtension, $allowedTypes)) {
                throw new \InvalidArgumentException(
                    "Tipo de archivo no permitido. Tipos permitidos: " . implode(', ', $allowedTypes)
                );
            }
        }

        // Check file size (in KB)
        if (isset($validations['max_file_size']) && is_numeric($validations['max_file_size'])) {
            $maxSizeKB = $validations['max_file_size'];
            $maxSizeBytes = $maxSizeKB * 1024;
            
            if ($file->getSize() > $maxSizeBytes) {
                throw new \InvalidArgumentException(
                    "El archivo es demasiado grande. Tamaño máximo permitido: {$maxSizeKB} KB"
                );
            }
        }

        // Additional MIME type validation for security
        $this->validateMimeType($file);
    }

    /**
     * Validate MIME type for security
     */
    private function validateMimeType(UploadedFile $file): void
    {
        $mimeType = $file->getMimeType();
        $allowedMimeTypes = [
            // Images
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
            
            // Documents
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            
            // Text files
            'text/plain',
            'text/csv',
            'application/rtf',
            
            // Archives
            'application/zip',
            'application/x-rar-compressed',
            'application/x-7z-compressed',
        ];

        if (!in_array($mimeType, $allowedMimeTypes)) {
            throw new \InvalidArgumentException(
                "Tipo de archivo no permitido por seguridad. Tipo detectado: {$mimeType}"
            );
        }
    }

    /**
     * Get the appropriate storage disk
     */
    private function getStorageDisk(): string
    {
        // Use S3 if configured, otherwise use local
        if (config('filesystems.default') === 's3' || env('FORM_FILES_DISK') === 's3') {
            return 's3_form_files';
        }
        
        return 'form_files';
    }

    /**
     * Get file URL based on storage disk
     */
    private function getFileUrl(string $path, string $disk): string
    {
        if ($disk === 's3_form_files') {
            return Storage::disk($disk)->url($path);
        }
        
        // For local storage, return a secure URL
        return route('form.file.download', ['encodedPath' => base64_encode($path)]);
    }

    /**
     * Delete a file
     */
    public function deleteFile(string $path, string $disk = null): bool
    {
        $disk = $disk ?: $this->getStorageDisk();
        
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        
        return false;
    }

    /**
     * Get file information without downloading
     */
    public function getFileInfo(string $path, string $disk = null): ?array
    {
        $disk = $disk ?: $this->getStorageDisk();
        
        if (!Storage::disk($disk)->exists($path)) {
            return null;
        }
        
        return [
            'path' => $path,
            'size' => Storage::disk($disk)->size($path),
            'last_modified' => Storage::disk($disk)->lastModified($path),
            'mime_type' => Storage::disk($disk)->mimeType($path),
            'url' => $this->getFileUrl($path, $disk),
        ];
    }
}
