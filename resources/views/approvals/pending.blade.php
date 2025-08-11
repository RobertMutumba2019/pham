@extends('layouts.app')

@section('title', 'Pending Approvals')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fa fa-clock"></i> My Pending Approvals</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Req. Number</th>
                                    <th>Title</th>
                                    <th>Requested By</th>
                                    <th>Department</th>
                                    <th>Priority</th>
                                    <th>Date Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingApprovals ?? [] as $approval)
                                <tr>
                                    <td>{{ $approval->requisition->req_number ?? 'N/A' }}</td>
                                    <td>{{ $approval->requisition->req_title ?? 'N/A' }}</td>
                                    <td>{{ ($approval->requisition->user->user_name ?? '') . ' ' . ($approval->requisition->user->user_surname ?? '') }}</td>
                                    <td>{{ $approval->requisition->user->department->dept_name ?? 'N/A' }}</td>
                                    <td>
                                        @if(($approval->requisition->req_priority ?? '') == 'Urgent')
                                            <span class="badge bg-danger">Urgent</span>
                                        @elseif(($approval->requisition->req_priority ?? '') == 'High')
                                            <span class="badge bg-warning">High</span>
                                        @else
                                            <span class="badge bg-info">Normal</span>
                                        @endif
                                    </td>
                                    <td>{{ $approval->created_at ? $approval->created_at->format('Y-m-d') : 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('requisitions.show', $approval->requisition_id ?? 0) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    onclick="approveRequisition({{ $approval->id ?? 0 }})">
                                                <i class="fa fa-check"></i> Approve
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="rejectRequisition({{ $approval->id ?? 0 }})">
                                                <i class="fa fa-times"></i> Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No pending approvals found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function approveRequisition(approvalId) {
    if (confirm('Are you sure you want to approve this requisition?')) {
        // Submit approval
        fetch(`/requisition-approvals/${approvalId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error approving requisition');
            }
        });
    }
}

function rejectRequisition(approvalId) {
    const reason = prompt('Please provide a reason for rejection:');
    if (reason) {
        fetch(`/requisition-approvals/${approvalId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ comments: reason })
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error rejecting requisition');
            }
        });
    }
}
</script>
@endsection
