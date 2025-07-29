@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>File Management</h2>
                <a href="{{ route('attachments.create') }}" class="btn btn-primary">
                    <i class="fa fa-upload"></i> Upload Files
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>All Attachments</h5>
                </div>
                <div class="card-body">
                    @if($attachments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>File</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Uploaded By</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attachments as $attachment)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fa {{ $attachment->file_icon }} fa-2x text-primary me-3"></i>
                                                    <div>
                                                        <div class="fw-bold">{{ $attachment->original_name }}</div>
                                                        @if($attachment->description)
                                                            <small class="text-muted">{{ $attachment->description }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">{{ $attachment->mime_type }}</span>
                                            </td>
                                            <td>{{ $attachment->human_readable_size }}</td>
                                            <td>{{ $attachment->uploader->user_name ?? 'Unknown' }}</td>
                                            <td>{{ $attachment->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('attachments.download', $attachment) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                    @if($attachment->isImage())
                                                        <a href="{{ route('attachments.preview', $attachment) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @endif
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteAttachment({{ $attachment->id }})">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $attachments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa fa-file-o fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No attachments found</h5>
                            <p class="text-muted">Upload your first file to get started.</p>
                            <a href="{{ route('attachments.create') }}" class="btn btn-primary">
                                <i class="fa fa-upload"></i> Upload Files
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteAttachment(id) {
    if (!confirm('Are you sure you want to delete this file?')) return;
    
    fetch(`{{ route('attachments.index') }}/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Delete failed. Please try again.');
        console.error('Delete error:', error);
    });
}
</script>
@endsection 