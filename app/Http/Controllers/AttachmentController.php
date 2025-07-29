<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attachment;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $attachments = Attachment::with(['uploader'])
            ->when($request->attachable_type, function($query, $type) {
                return $query->where('attachable_type', $type);
            })
            ->when($request->attachable_id, function($query, $id) {
                return $query->where('attachable_id', $id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('attachments.index', compact('attachments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('attachments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'attachable_type' => 'required|string',
            'attachable_id' => 'required|integer',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $attachment = $this->fileUploadService->uploadFile(
                $request->file('file'),
                $request->attachable_type,
                $request->attachable_id,
                $request->description
            );

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'attachment' => $attachment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Attachment $attachment)
    {
        return view('attachments.show', compact('attachment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attachment $attachment)
    {
        return view('attachments.edit', compact('attachment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attachment $attachment)
    {
        $request->validate([
            'description' => 'nullable|string|max:500',
        ]);

        $attachment->update([
            'description' => $request->description,
        ]);

        return redirect()->route('attachments.index')
            ->with('success', 'Attachment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attachment $attachment)
    {
        try {
            $this->fileUploadService->deleteFile($attachment);

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download file
     */
    public function download(Attachment $attachment)
    {
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download(
            $attachment->file_path,
            $attachment->original_name
        );
    }

    /**
     * Preview file (for images)
     */
    public function preview(Attachment $attachment)
    {
        if (!$this->fileUploadService->isImage($attachment->mime_type)) {
            abort(404, 'File cannot be previewed');
        }

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'File not found');
        }

        return response()->file(Storage::disk('public')->path($attachment->file_path));
    }

    /**
     * Get attachments for a specific model
     */
    public function getAttachments(Request $request)
    {
        $request->validate([
            'attachable_type' => 'required|string',
            'attachable_id' => 'required|integer',
        ]);

        $attachments = Attachment::where('attachable_type', $request->attachable_type)
            ->where('attachable_id', $request->attachable_id)
            ->with('uploader')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($attachments);
    }

    /**
     * Bulk upload files
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240',
            'attachable_type' => 'required|string',
            'attachable_id' => 'required|integer',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $attachments = $this->fileUploadService->uploadMultipleFiles(
                $request->file('files'),
                $request->attachable_type,
                $request->attachable_id,
                $request->description
            );

            return response()->json([
                'success' => true,
                'message' => count($attachments) . ' files uploaded successfully',
                'attachments' => $attachments
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
