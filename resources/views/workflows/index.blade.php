@extends('layouts.app')

@section('title', 'Approval Workflows')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-sitemap"></i> Approval Workflows</h4>
                    <a href="{{ route('approval-workflows.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> New Workflow
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchWorkflow" placeholder="Search workflows...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="departmentFilter">
                                <option value="">All Departments</option>
                                @foreach($departments ?? [] as $department)
                                    <option value="{{ $department->id }}">{{ $department->dept_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-info w-100" id="filterBtn">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>

                    <!-- Workflows Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="workflowsTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Workflow Name</th>
                                    <th>Department</th>
                                    <th>Approval Levels</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($workflows ?? [] as $workflow)
                                <tr>
                                    <td>{{ $workflow->workflow_name ?? 'N/A' }}</td>
                                    <td>{{ $workflow->department->dept_name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $workflow->approval_levels ?? 0 }} Levels</span>
                                    </td>
                                    <td>
                                        @if(($workflow->is_active ?? false))
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $workflow->created_at ? $workflow->created_at->format('Y-m-d') : 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('approval-workflows.show', $workflow->id ?? 0) }}" 
                                               class="btn btn-sm btn-info" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('approval-workflows.edit', $workflow->id ?? 0) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-secondary toggle-status" 
                                                    data-id="{{ $workflow->id ?? 0 }}" 
                                                    title="{{ ($workflow->is_active ?? false) ? 'Deactivate' : 'Activate' }}">
                                                <i class="fa fa-{{ ($workflow->is_active ?? false) ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No workflows found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($workflows) && method_exists($workflows, 'links'))
                        <div class="d-flex justify-content-center">
                            {{ $workflows->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Workflow Statistics -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Total Workflows</h5>
                        <h3>{{ $totalWorkflows ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-sitemap fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Active Workflows</h5>
                        <h3>{{ $activeWorkflows ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Pending Approvals</h5>
                        <h3>{{ $pendingApprovals ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Departments</h5>
                        <h3>{{ $totalDepartments ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-building fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle workflow status
    document.addEventListener('click', function(e) {
        if (e.target.closest('.toggle-status')) {
            const btn = e.target.closest('.toggle-status');
            const workflowId = btn.getAttribute('data-id');
            
            if (confirm('Are you sure you want to toggle this workflow status?')) {
                fetch(`/approval-workflows/${workflowId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Error toggling workflow status');
                    }
                });
            }
        }
    });

    // Initialize DataTable if available
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#workflowsTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[4, 'desc']] // Sort by created date
        });
    }
});
</script>
@endsection
