@props(['attachable_type', 'attachable_id', 'description' => null])

<div class="file-upload-component" data-attachable-type="{{ $attachable_type }}" data-attachable-id="{{ $attachable_id }}">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fa fa-paperclip"></i> Attachments
            </h5>
        </div>
        <div class="card-body">
            <!-- File Upload Area -->
            <div class="upload-area mb-3" id="upload-area-{{ $attachable_id }}">
                <div class="upload-zone border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <i class="fa fa-cloud-upload fa-3x text-gray-400 mb-3"></i>
                    <p class="text-gray-600 mb-2">Drag and drop files here or click to browse</p>
                    <p class="text-sm text-gray-500">Supported formats: PDF, DOC, DOCX, XLS, XLSX, Images, TXT (Max: 10MB)</p>
                    <input type="file" class="file-input" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.txt" style="display: none;">
                    <button type="button" class="btn btn-primary mt-2" onclick="document.querySelector('#upload-area-{{ $attachable_id }} .file-input').click()">
                        <i class="fa fa-upload"></i> Choose Files
                    </button>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="upload-progress" style="display: none;">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
                <small class="text-muted">Uploading...</small>
            </div>

            <!-- Attachments List -->
            <div class="attachments-list">
                <h6>Uploaded Files</h6>
                <div class="attachments-container" id="attachments-{{ $attachable_id }}">
                    <!-- Attachments will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const attachableType = '{{ $attachable_type }}';
    const attachableId = '{{ $attachable_id }}';
    
    // Load existing attachments
    loadAttachments(attachableType, attachableId);
    
    // File upload handling
    const uploadArea = document.getElementById(`upload-area-${attachableId}`);
    const fileInput = uploadArea.querySelector('.file-input');
    const progressBar = uploadArea.parentElement.querySelector('.upload-progress');
    
    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('border-primary');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-primary');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-primary');
        const files = e.dataTransfer.files;
        uploadFiles(files);
    });
    
    // File input change
    fileInput.addEventListener('change', function(e) {
        uploadFiles(e.target.files);
    });
    
    function uploadFiles(files) {
        if (files.length === 0) return;
        
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }
        formData.append('attachable_type', attachableType);
        formData.append('attachable_id', attachableId);
        formData.append('description', '{{ $description }}');
        
        // Show progress
        progressBar.style.display = 'block';
        
        fetch('{{ route("attachments.bulk-upload") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            progressBar.style.display = 'none';
            if (data.success) {
                // Reload attachments
                loadAttachments(attachableType, attachableId);
                // Clear file input
                fileInput.value = '';
                // Show success message
                showAlert('Files uploaded successfully!', 'success');
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            progressBar.style.display = 'none';
            showAlert('Upload failed. Please try again.', 'error');
            console.error('Upload error:', error);
        });
    }
    
    function loadAttachments(type, id) {
        fetch(`{{ route('attachments.get-attachments') }}?attachable_type=${type}&attachable_id=${id}`)
        .then(response => response.json())
        .then(attachments => {
            const container = document.getElementById(`attachments-${id}`);
            container.innerHTML = '';
            
            if (attachments.length === 0) {
                container.innerHTML = '<p class="text-muted">No attachments uploaded yet.</p>';
                return;
            }
            
            attachments.forEach(attachment => {
                const attachmentHtml = `
                    <div class="attachment-item border rounded p-2 mb-2" data-id="${attachment.id}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fa ${attachment.file_icon} fa-2x text-primary me-3"></i>
                                <div>
                                    <div class="fw-bold">${attachment.original_name}</div>
                                    <small class="text-muted">
                                        ${attachment.human_readable_size} • 
                                        Uploaded by ${attachment.uploader?.user_name || 'Unknown'} • 
                                        ${new Date(attachment.created_at).toLocaleDateString()}
                                    </small>
                                </div>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('attachments.download', ':id') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-download"></i>
                                </a>
                                ${attachment.is_image ? `<a href="{{ route('attachments.preview', ':id') }}" class="btn btn-sm btn-outline-info" target="_blank">
                                    <i class="fa fa-eye"></i>
                                </a>` : ''}
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteAttachment(${attachment.id})">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `.replace(/:id/g, attachment.id);
                
                container.innerHTML += attachmentHtml;
            });
        })
        .catch(error => {
            console.error('Error loading attachments:', error);
        });
    }
    
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
                loadAttachments(attachableType, attachableId);
                showAlert('File deleted successfully!', 'success');
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            showAlert('Delete failed. Please try again.', 'error');
            console.error('Delete error:', error);
        });
    }
    
    function showAlert(message, type) {
        // Create alert element
        const alert = document.createElement('div');
        alert.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert at top of page
        document.body.insertBefore(alert, document.body.firstChild);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
});
</script>

<style>
.upload-zone {
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-zone:hover {
    border-color: #007bff !important;
    background-color: #f8f9fa;
}

.upload-zone.border-primary {
    border-color: #007bff !important;
    background-color: #e3f2fd;
}

.attachment-item {
    transition: all 0.2s ease;
}

.attachment-item:hover {
    background-color: #f8f9fa;
}

.progress {
    height: 20px;
}
</style> 