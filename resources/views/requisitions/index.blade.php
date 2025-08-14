@extends('layouts.app')

@section('title', 'All Requisitions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-list"></i> All Requisitions</h4>
                    <a href="{{ route('requisitions.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> New Requisition
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="searchReq" placeholder="Search requisitions...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="1">Pending</option>
                                <option value="2">Approved</option>
                                <option value="3">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="dateFilter">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info" id="filterBtn">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>

                    <!-- Requisitions Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="requisitionsTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Req. Number</th>
                                    <th>Title</th>
                                    <th>Department</th>
                                    <th>Requested By</th>
                                    <th>Date Added</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requisitions ?? [] as $requisition)
                                <tr>
                                    <td>{{ $requisition->req_number ?? 'N/A' }}</td>
                                    <td>{{ $requisition->req_title ?? 'N/A' }}</td>
                                    <td>{{ $requisition->user->department->dept_name ?? 'N/A' }}</td>
                                    <td>{{ ($requisition->user->user_name ?? '') . ' ' . ($requisition->user->user_surname ?? '') }}</td>
                                    <td>{{ $requisition->req_date_added ?? 'N/A' }}</td>
                                    <td>
                                        @if(isset($requisition->req_status_id))
                                            @if($requisition->req_status_id == 1)
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($requisition->req_status_id == 2)
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($requisition->req_status_id == 3)
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-secondary">Unknown</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('requisitions.show', $requisition->id ?? 0) }}" class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('requisitions.edit', $requisition->id ?? 0) }}" class="btn btn-sm btn-warning">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No requisitions found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($requisitions) && method_exists($requisitions, 'links'))
                        <div class="d-flex justify-content-center">
                            {{ $requisitions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable if available
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#requisitionsTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[4, 'desc']] // Sort by date added
        });
    }
});
</script>
@endsection
