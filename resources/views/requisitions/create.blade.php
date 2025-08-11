@extends('layouts.app')

@section('title', 'New Requisition')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fa fa-plus"></i> New Requisition</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('requisitions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="req_title" class="form-label">Requisition Title *</label>
                                    <input type="text" class="form-control @error('req_title') is-invalid @enderror" 
                                           id="req_title" name="req_title" value="{{ old('req_title') }}" required>
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
                                        <option value="Normal" {{ old('req_priority') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="High" {{ old('req_priority') == 'High' ? 'selected' : '' }}>High</option>
                                        <option value="Urgent" {{ old('req_priority') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
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
                                           id="req_ref" name="req_ref" value="{{ old('req_ref') }}">
                                    @error('req_ref')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="req_date_needed" class="form-label">Date Needed</label>
                                    <input type="date" class="form-control @error('req_date_needed') is-invalid @enderror" 
                                           id="req_date_needed" name="req_date_needed" value="{{ old('req_date_needed') }}">
                                    @error('req_date_needed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="req_description" class="form-label">Description *</label>
                            <textarea class="form-control @error('req_description') is-invalid @enderror" 
                                      id="req_description" name="req_description" rows="4" required>{{ old('req_description') }}</textarea>
                            @error('req_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="req_justification" class="form-label">Justification</label>
                            <textarea class="form-control @error('req_justification') is-invalid @enderror" 
                                      id="req_justification" name="req_justification" rows="3">{{ old('req_justification') }}</textarea>
                            @error('req_justification')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Items Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5>Requisition Items</h5>
                            </div>
                            <div class="card-body">
                                <div id="items-container">
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
                                </div>
                                <button type="button" class="btn btn-secondary" id="add-item">
                                    <i class="fa fa-plus"></i> Add Item
                                </button>
                            </div>
                        </div>

                        <!-- Attachments -->
                        <div class="mb-3 mt-4">
                            <label for="attachments" class="form-label">Attachments</label>
                            <input type="file" class="form-control @error('attachments') is-invalid @enderror" 
                                   id="attachments" name="attachments[]" multiple>
                            <small class="form-text text-muted">You can select multiple files</small>
                            @error('attachments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('requisitions.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                            <div>
                                <button type="submit" name="action" value="draft" class="btn btn-warning">
                                    <i class="fa fa-save"></i> Save as Draft
                                </button>
                                <button type="submit" name="action" value="submit" class="btn btn-primary">
                                    <i class="fa fa-paper-plane"></i> Submit Requisition
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
    let itemIndex = 1;
    
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
        
        // Show remove buttons if more than one item
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
</script>
@endsection
