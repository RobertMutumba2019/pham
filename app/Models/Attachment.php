<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'original_name',
        'file_path',
        'file_size',
        'mime_type',
        'uploader_id',
        'attachable_type',
        'attachable_id',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'file_size' => 'integer',
    ];

    // Relationships
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function attachable()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('attachable_type', $type);
    }

    public function scopeByAttachable($query, $type, $id)
    {
        return $query->where('attachable_type', $type)
                    ->where('attachable_id', $id);
    }

    // Methods
    public function getHumanReadableSizeAttribute()
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, 2) . ' ' . $units[$i];
    }

    public function getFileIconAttribute()
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

        return $icons[$this->mime_type] ?? 'fa-file-o';
    }

    public function isImage()
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function isDocument()
    {
        $documentTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
        ];

        return in_array($this->mime_type, $documentTypes);
    }

    public function getDownloadUrlAttribute()
    {
        return \Storage::disk('public')->url($this->file_path);
    }

    public function getPreviewUrlAttribute()
    {
        if ($this->isImage()) {
            return \Storage::disk('public')->url($this->file_path);
        }

        return null;
    }
}
