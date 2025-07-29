<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Attachment;

class FileUploadService
{
    /**
     * Upload a file and create attachment record
     */
    public function uploadFile(UploadedFile $file, $attachable_type, $attachable_id, $description = null)
    {
        // Validate file
        $this->validateFile($file);

        // Generate unique filename
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::random(40) . '.' . $extension;
        
        // Store file
        $filePath = $file->storeAs('attachments/' . date('Y/m'), $fileName, 'public');
        
        // Create attachment record
        $attachment = Attachment::create([
            'file_name' => $fileName,
            'original_name' => $originalName,
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploader_id' => auth()->id(),
            'attachable_type' => $attachable_type,
            'attachable_id' => $attachable_id,
            'description' => $description,
        ]);

        return $attachment;
    }

    /**
     * Validate uploaded file
     */
    public function validateFile(UploadedFile $file)
    {
        $maxSize = 10 * 1024 * 1024; // 10MB
        $allowedMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'image/jpeg',
            'image/png',
            'image/gif',
            'text/plain',
        ];

        if ($file->getSize() > $maxSize) {
            throw new \Exception('File size exceeds maximum limit of 10MB');
        }

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('File type not allowed. Allowed types: PDF, DOC, DOCX, XLS, XLSX, Images, TXT');
        }

        return true;
    }

    /**
     * Delete file and attachment record
     */
    public function deleteFile(Attachment $attachment)
    {
        // Delete physical file
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        // Delete attachment record
        $attachment->delete();

        return true;
    }

    /**
     * Get file download URL
     */
    public function getDownloadUrl(Attachment $attachment)
    {
        return Storage::disk('public')->url($attachment->file_path);
    }

    /**
     * Get file preview URL (for images)
     */
    public function getPreviewUrl(Attachment $attachment)
    {
        if (str_starts_with($attachment->mime_type, 'image/')) {
            return Storage::disk('public')->url($attachment->file_path);
        }

        return null;
    }

    /**
     * Get file size in human readable format
     */
    public function getHumanReadableSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file icon based on mime type
     */
    public function getFileIcon($mimeType)
    {
        $icons = [
            'application/pdf' => 'fa-file-pdf-o',
            'application/msword' => 'fa-file-word-o',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'fa-file-word-o',
            'application/vnd.ms-excel' => 'fa-file-excel-o',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fa-file-excel-o',
            'image/jpeg' => 'fa-file-image-o',
            'image/png' => 'fa-file-image-o',
            'image/gif' => 'fa-file-image-o',
            'text/plain' => 'fa-file-text-o',
        ];

        return $icons[$mimeType] ?? 'fa-file-o';
    }

    /**
     * Bulk upload files
     */
    public function uploadMultipleFiles($files, $attachable_type, $attachable_id, $description = null)
    {
        $attachments = [];

        foreach ($files as $file) {
            try {
                $attachment = $this->uploadFile($file, $attachable_type, $attachable_id, $description);
                $attachments[] = $attachment;
            } catch (\Exception $e) {
                // Log error but continue with other files
                \Log::error('File upload failed: ' . $e->getMessage());
            }
        }

        return $attachments;
    }

    /**
     * Check if file is image
     */
    public function isImage($mimeType)
    {
        return str_starts_with($mimeType, 'image/');
    }

    /**
     * Check if file is document
     */
    public function isDocument($mimeType)
    {
        $documentTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
        ];

        return in_array($mimeType, $documentTypes);
    }
} 