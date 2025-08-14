@extends('layouts.app')

@section('title', 'View Requisition')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-eye"></i> Requisition Details</h4>
                    <div>
                        <a href="{{ route('requisitions.edit', $requisition->id ?? 0) }}" class="btn btn-warning">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('requisitions.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($requisition))
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Requisition Number:</th>
                                    <td>{{ $requisition->req_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Title:</th>
                                    <td>{{ $requisition->req_title ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Priority:</th>
                                    <td>
                                        @if(($requisition->req_priority ?? '') == 'High')
                                            <span class="badge bg-warning">High</span>
                                        @elseif(($requisition->req_priority ?? '') == 'Urgent')
                                            <span class="badge bg-danger">Urgent</span>
                                        @else
                                            <span class="badge bg-info">Normal</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Reference:</th>
                                    <td>{{ $requisition->req_ref ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Requested By:</th>
                                    <td>{{ ($requisition->user->user_name ?? '') . ' ' . ($requisition->user->user_surname ?? '') }}</td>
                                </tr>
                                <tr>
                                    <th>Department:</th>
                                    <td>{{ $requisition->user->department->dept_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Date Added:</th>
                                    <td>{{ $requisition->req_date_added ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if(($requisition->req_status_id ?? 0) == 1)
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif(($requisition->req_status_id ?? 0) == 2)
                                            <span class="badge bg-success">Approved</span>
                                        @elseif(($requisition->req_status_id ?? 0) == 3)
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary">Unknown</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Description</h5>
                            <p class="border p-3 bg-light">{{ $requisition->req_description ?? 'No description provided' }}</p>
                        </div>
                    </div>

                    @if($requisition->req_justification ?? '')
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Justification</h5>
                            <p class="border p-3 bg-light">{{ $requisition->req_justification }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Items Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Requisition Items</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Description</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Estimated Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($requisition->items ?? [] as $item)
                                        <tr>
                                            <td>{{ $item->item_description ?? 'N/A' }}</td>
                                            <td>{{ $item->item_quantity ?? 'N/A' }}</td>
                                            <td>{{ $item->item_unit ?? 'N/A' }}</td>
                                            <td>{{ $item->item_estimated_cost ? number_format($item->item_estimated_cost, 2) : 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No items found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments Section -->
                    @if(isset($requisition->attachments) && $requisition->attachments->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Attachments</h5>
                            <div class="list-group">
                                @foreach($requisition->attachments as $attachment)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fa fa-file"></i> {{ $attachment->original_name ?? 'Unknown File' }}
                                        <small class="text-muted">({{ $attachment->file_size ? number_format($attachment->file_size / 1024, 2) . ' KB' : 'Unknown size' }})</small>
                                    </div>
                                    <div>
                                        <a href="{{ route('attachments.download', $attachment->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Approval History -->
                    @if(isset($requisition->approvals) && $requisition->approvals->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Approval History</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Approver</th>
                                            <th>Action</th>
                                            <th>Date</th>
                                            <th>Comments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requisition->approvals as $approval)
                                        <tr>
                                            <td>{{ ($approval->approver->user_name ?? '') . ' ' . ($approval->approver->user_surname ?? '') }}</td>
                                            <td>
                                                @if($approval->status == 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($approval->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $approval->action_date ?? 'N/A' }}</td>
                                            <td>{{ $approval->comments ?? 'No comments' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    @else
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i> Requisition not found or data unavailable.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
