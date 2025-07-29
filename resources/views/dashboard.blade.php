@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Dashboard</h2>
            <p>Welcome to the Store Management System</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Requisitions</h5>
                    <p class="card-text">
                        @php
                            try {
                                $totalRequisitions = \App\Models\Requisition::count();
                                $pendingRequisitions = \App\Models\Requisition::whereHas('status', function($q) { $q->where('name', 'Pending'); })->count();
                                $approvedRequisitions = \App\Models\Requisition::whereHas('status', function($q) { $q->where('name', 'Approved'); })->count();
                                $rejectedRequisitions = \App\Models\Requisition::whereHas('status', function($q) { $q->where('name', 'Rejected'); })->count();
                            } catch (\Exception $e) {
                                $totalRequisitions = 0;
                                $pendingRequisitions = 0;
                                $approvedRequisitions = 0;
                                $rejectedRequisitions = 0;
                            }
                        @endphp
                        <strong>Total:</strong> {{ $totalRequisitions }}<br>
                        <strong>Pending:</strong> {{ $pendingRequisitions }}<br>
                        <strong>Approved:</strong> {{ $approvedRequisitions }}<br>
                        <strong>Rejected:</strong> {{ $rejectedRequisitions }}
                    </p>
                    <a href="{{ route('requisitions.index') }}" class="btn btn-primary">View All</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pending Approvals</h5>
                    <p class="card-text">
                        @php
                            try {
                                $myPendingApprovals = \App\Models\RequisitionApproval::where('approver_id', auth()->id())->where('status', 'pending')->count();
                            } catch (\Exception $e) {
                                $myPendingApprovals = 0;
                            }
                        @endphp
                        <strong>My Approvals:</strong> {{ $myPendingApprovals }}
                    </p>
                    <a href="{{ route('requisition-approvals.index') }}" class="btn btn-warning">View Pending</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Users</h5>
                    <p class="card-text">
                        @php
                            try {
                                $totalUsers = \App\Models\User::count();
                                $activeUsers = \App\Models\User::where('user_active', 1)->count();
                            } catch (\Exception $e) {
                                $totalUsers = 0;
                                $activeUsers = 0;
                            }
                        @endphp
                        <strong>Total Users:</strong> {{ $totalUsers }}<br>
                        <strong>Active Users:</strong> {{ $activeUsers }}
                    </p>
                    <a href="{{ route('users.index') }}" class="btn btn-info">Manage Users</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">File Management</h5>
                    <p class="card-text">
                        @php
                            try {
                                $totalAttachments = \App\Models\Attachment::count();
                                $recentAttachments = \App\Models\Attachment::orderBy('created_at', 'desc')->limit(5)->count();
                            } catch (\Exception $e) {
                                $totalAttachments = 0;
                                $recentAttachments = 0;
                            }
                        @endphp
                        <strong>Total Files:</strong> {{ $totalAttachments }}<br>
                        <strong>Recent Uploads:</strong> {{ $recentAttachments }}
                    </p>
                    <a href="{{ route('attachments.index') }}" class="btn btn-success">Manage Files</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Workflows</h5>
                    <p class="card-text">
                        <strong>Active Workflows:</strong> {{ \App\Models\ApprovalWorkflow::where('is_active', true)->count() }}
                    </p>
                    <a href="{{ route('approval-workflows.index') }}" class="btn btn-success">Manage Workflows</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Requisitions</h5>
                </div>
                <div class="card-body">
                    @php
                        $recentRequisitions = \App\Models\Requisition::with('status')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($recentRequisitions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRequisitions as $requisition)
                                        <tr>
                                            <td>{{ $requisition->req_number ?? 'N/A' }}</td>
                                            <td>{{ $requisition->req_title ?? 'N/A' }}</td>
                                            <td>
                                                @if($requisition->status)
                                                    <span class="badge" style="background-color: {{ $requisition->status->color }}">
                                                        {{ $requisition->status->name }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">Unknown</span>
                                                @endif
                                            </td>
                                            <td>{{ $requisition->created_at ? $requisition->created_at->format('Y-m-d') : 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No requisitions found.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>My Pending Approvals</h5>
                </div>
                <div class="card-body">
                    @php
                        try {
                            $pendingApprovals = \App\Models\RequisitionApproval::with(['requisition', 'requisition.status'])
                                ->where('approver_id', auth()->id())
                                ->where('status', 'pending')
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        } catch (\Exception $e) {
                            $pendingApprovals = collect([]);
                        }
                    @endphp
                    
                    @if($pendingApprovals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Requisition</th>
                                        <th>Level</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingApprovals as $approval)
                                        <tr>
                                            <td>{{ $approval->requisition->req_number ?? 'N/A' }}</td>
                                            <td>Level {{ $approval->approval_level }}</td>
                                            <td>{{ $approval->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <a href="{{ route('requisition-approvals.show', $approval) }}" class="btn btn-sm btn-primary">Review</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No pending approvals.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
