@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Territory Vehicle Request & Return</h2>
    <h4>Vehicle Requests</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Request Number</th>
                <th>Requestor</th>
                <th>Date Added</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vehicleRequests as $req)
                <tr>
                    <td>{{ $loop->iteration + ($vehicleRequests->currentPage() - 1) * $vehicleRequests->perPage() }}</td>
                    <td>{{ $req->request_number ?? $req->id }}</td>
                    <td>{{ $req->requestor ?? $req->requestor_id }}</td>
                    <td>{{ $req->date_added ?? $req->created_at }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No vehicle requests found.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $vehicleRequests->withQueryString()->links() }}

    <h4>Vehicle Returns</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Return Number</th>
                <th>Requestor</th>
                <th>Date Added</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vehicleReturns as $ret)
                <tr>
                    <td>{{ $loop->iteration + ($vehicleReturns->currentPage() - 1) * $vehicleReturns->perPage() }}</td>
                    <td>{{ $ret->return_number ?? $ret->id }}</td>
                    <td>{{ $ret->requestor ?? $ret->requestor_id }}</td>
                    <td>{{ $ret->date_added ?? $ret->created_at }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No vehicle returns found.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $vehicleReturns->withQueryString()->links() }}
</div>
@endsection 

@section('content')
<div class="container">
    <h2>Territory Vehicle Request & Return</h2>
    <h4>Vehicle Requests</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Request Number</th>
                <th>Requestor</th>
                <th>Date Added</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vehicleRequests as $req)
                <tr>
                    <td>{{ $loop->iteration + ($vehicleRequests->currentPage() - 1) * $vehicleRequests->perPage() }}</td>
                    <td>{{ $req->request_number ?? $req->id }}</td>
                    <td>{{ $req->requestor ?? $req->requestor_id }}</td>
                    <td>{{ $req->date_added ?? $req->created_at }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No vehicle requests found.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $vehicleRequests->withQueryString()->links() }}

    <h4>Vehicle Returns</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Return Number</th>
                <th>Requestor</th>
                <th>Date Added</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vehicleReturns as $ret)
                <tr>
                    <td>{{ $loop->iteration + ($vehicleReturns->currentPage() - 1) * $vehicleReturns->perPage() }}</td>
                    <td>{{ $ret->return_number ?? $ret->id }}</td>
                    <td>{{ $ret->requestor ?? $ret->requestor_id }}</td>
                    <td>{{ $ret->date_added ?? $ret->created_at }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No vehicle returns found.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $vehicleReturns->withQueryString()->links() }}
</div>
@endsection 