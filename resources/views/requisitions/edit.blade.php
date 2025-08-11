@extends('layouts.app')

@section('title', 'Edit Requisition')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fa fa-edit"></i> Edit Requisition</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('requisitions.update', $requisition->req_id ?? 0) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="req_title" class="form-label">Requisition Title *</label>
                                    <input type="text" class="form-control @error('req_title') is-invalid @enderror" 
                                           id="req_title" name="req_title" value="{{ old('req_title', $requisition->req_title ?? '') }}" required>
                                    @error('req_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="req_priority" class="form-label">Priority</label>
                                    <select class="form-control @error('req_priority') is-invalid @enderror" 
                                            id="req_priority" name="req_priority">
                                        <option value="Normal" {{ old('req_priority', $requisition->req_priority ?? '') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="High" {{ old('req_priority', $requisition->req_priority ?? '') == 'High' ? 'selected' : '' }}>High</option>
                                        <option value="Urgent" {{ old('req_priority', $requisition->req_priority ?? '') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                    @error('req_priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="req_ref" class="form-label">Reference</label>
                                    <input type="text" class="form-control @error('req_ref') is-invalid @enderror" 
                                           id="req_ref" name="req_ref" value="{{ old('req_ref', $requisition->req_ref ?? '') }}">
                                    @error('req_ref')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="req_date_needed" class="form-label">Date Needed</label>
                                    <input type="date" class="form-control @error('req_date_needed') is-invalid @enderror" 
                                           id="req_date_needed" name="req_date_needed" value="{{ old('req_date_needed', $requisition->req_date_needed ?? '') }}">
                                    @error('req_date_needed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="req_description" class="form-label">Description *</label>
                            <textarea class="form-control @error('req_description') is-invalid @enderror" 
                                      id="req_description" name="req_description" rows="4" required>{{ old('req_description', $requisition->req_description ?? '') }}</textarea>
                            @error('req_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="req_justification" class="form-label">Justification</label>
                            <textarea class="form-control @error('req_justification') is-invalid @enderror" 
                                      id="req_justification" name="req_justification" rows="3">{{ old('req_justification', $requisition->req_justification ?? '') }}</textarea>
                            @error('req_justification')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Existing Items Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5>Requisition Items</h5>
                            </div>
                            <div class="card-body">
                                <div id="items-container">
                                    @forelse($requisition->items ?? [] as $index => $item)
                                    <div class="item-row row mb-3">
                                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id ?? '' }}">
                                        <div class="col-md-4">
                                            @if($index == 0)<label class="form-label">Item Description</label>@endif
                                            <input type="text" class="form-control" name="items[{{ $index }}][description]" 
                                                   value="{{ $item->item_description ?? '' }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            @if($index == 0)<label class="form-label">Quantity</label>@endif
                                            <input type="number" class="form-control" name="items[{{ $index }}][quantity]" 
                                                   value="{{ $item->item_quantity ?? '' }}" min="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            @if($index == 0)<label class="form-label">Unit</label>@endif
                                            <input type="text" class="form-control" name="items[{{ $index }}][unit]" 
                                                   value="{{ $item->item_unit ?? '' }}" placeholder="e.g., pcs, kg">
                                        </div>
                                        <div class="col-md-3">
                                            @if($index == 0)<label class="form-label">Estimated Cost</label>@endif
                                            <input type="number" class="form-control" name="items[{{ $index }}][estimated_cost]" 
                                                   value="{{ $item->item_estimated_cost ?? '' }}" step="0.01">
                                        </div>
                                        <div class="col-md-1">
                                            @if($index == 0)<label class="form-label">&nbsp;</label>@endif
                                            <button type="button" class="btn btn-danger btn-sm remove-item" 
                                                    style="{{ count($requisition->items ?? []) <= 1 ? 'display:none;' : '' }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="item-row row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Item Description</label>
                                            <input type="text" class="form-control" name="items[0][description]" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" class="form-control" name="items[0][quantity]" min="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Unit</label>
                                            <input type="text" class="form-control" name="items[0][unit]" placeholder="e.g., pcs, kg">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Estimated Cost</label>
                                            <input type="number" class="form-control" name="items[0][estimated_cost]" step="0.01">
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-sm remove-item" style="display:none;">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                                <button type="button" class="btn btn-secondary" id="add-item">
                                    <i class="fa fa-plus"></i> Add Item
                                </button>
                            </div>
                        </div>

                        <!-- Current Attachments -->
                        @if(isset($requisition->attachments) && $requisition->attachments->count() > 0)
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5>Current Attachments</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    @foreach($requisition->attachments as $attachment)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fa fa-file"></i> {{ $attachment->original_name ?? 'Unknown File' }}
                                            <small class="text-muted">({{ $attachment->file_size ? number_format($attachment->file_size / 1024, 2) . ' KB' : 'Unknown size' }})</small>
                                        </div>
                                        <div>
                                            <a href="{{ route('attachments.download', $attachment->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeAttachment({{ $attachment->id }})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- New Attachments -->
                        <div class="mb-3 mt-4">
                            <label for="attachments" class="form-label">Add New Attachments</label>
                            <input type="file" class="form-control @error('attachments') is-invalid @enderror" 
                                   id="attachments" name="attachments[]" multiple>
                            <small class="form-text text-muted">You can select multiple files</small>
                            @error('attachments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('requisitions.show', $requisition->req_id ?? 0) }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back to View
                            </a>
                            <div>
                                <button type="submit" name="action" value="update" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update Requisition
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = {{ count($requisition->items ?? []) }};
    
    // Add new item row
    document.getElementById('add-item').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const newRow = document.createElement('div');
        newRow.className = 'item-row row mb-3';
        newRow.innerHTML = `
            <div class="col-md-4">
                <input type="text" class="form-control" name="items[${itemIndex}][description]" required>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="items[${itemIndex}][quantity]" min="1" required>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="items[${itemIndex}][unit]" placeholder="e.g., pcs, kg">
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" name="items[${itemIndex}][estimated_cost]" step="0.01">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newRow);
        itemIndex++;
        updateRemoveButtons();
    });
    
    // Remove item row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            e.target.closest('.item-row').remove();
            updateRemoveButtons();
        }
    });
    
    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach((row, index) => {
            const removeBtn = row.querySelector('.remove-item');
            if (rows.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }
});

function removeAttachment(attachmentId) {
    if (confirm('Are you sure you want to remove this attachment?')) {
        fetch(`/attachments/${attachmentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error removing attachment');
            }
        });
    }
}
</script>
@endsection
