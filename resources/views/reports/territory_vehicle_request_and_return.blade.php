@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Territory Vehicle Request & Return Report</h2>
    
    <h4>Vehicle Requests</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Request Number</th>
                <th>Requestor</th>
                <th>Vehicle Type</th>
                <th>Purpose</th>
                <th>Required Date</th>
                <th>Destination</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vehicleRequests as $req)
                <tr>
                    <td>{{ $loop->iteration + ($vehicleRequests->currentPage() - 1) * $vehicleRequests->perPage() }}</td>
                    <td>{{ $req->request_number ?? 'N/A' }}</td>
                    <td>{{ $req->user_name ?? 'N/A' }} {{ $req->user_surname ?? '' }}</td>
                    <td>{{ $req->vehicle_type ?? 'N/A' }}</td>
                    <td>{{ $req->vehicle_purpose ?? 'N/A' }}</td>
                    <td>{{ $req->required_date ?? 'N/A' }}</td>
                    <td>{{ $req->destination ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-{{ $req->status == 'approved' ? 'success' : ($req->status == 'rejected' ? 'danger' : ($req->status == 'completed' ? 'info' : 'warning')) }}">
                            {{ ucfirst($req->status ?? 'pending') }}
                        </span>
                    </td>
                    <td>{{ $req->created_at ? \Carbon\Carbon::parse($req->created_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                </tr>
            @empty
                <tr><td colspan="9">No vehicle requests found.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $vehicleRequests->withQueryString()->links() }}

    <h4>Vehicle Returns</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Return Date</th>
                <th>Returned By</th>
                <th>Return Location</th>
                <th>Mileage</th>
                <th>Vehicle Condition</th>
                <th>Received By</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vehicleReturns as $ret)
                <tr>
                    <td>{{ $loop->iteration + ($vehicleReturns->currentPage() - 1) * $vehicleReturns->perPage() }}</td>
                    <td>{{ $ret->return_date ?? 'N/A' }}</td>
                    <td>{{ $ret->returned_by_name ?? 'N/A' }} {{ $ret->returned_by_surname ?? '' }}</td>
                    <td>{{ $ret->return_location ?? 'N/A' }}</td>
                    <td>{{ $ret->mileage_covered ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-{{ $ret->vehicle_condition == 'excellent' ? 'success' : ($ret->vehicle_condition == 'good' ? 'info' : ($ret->vehicle_condition == 'fair' ? 'warning' : 'danger')) }}">
                            {{ ucfirst($ret->vehicle_condition ?? 'good') }}
                        </span>
                    </td>
                    <td>{{ $ret->received_by_name ?? 'N/A' }} {{ $ret->received_by_surname ?? '' }}</td>
                    <td>{{ $ret->created_at ? \Carbon\Carbon::parse($ret->created_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                </tr>
            @empty
                <tr><td colspan="8">No vehicle returns found.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $vehicleReturns->withQueryString()->links() }}
</div>
@endsection 