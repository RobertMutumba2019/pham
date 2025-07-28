@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Serial Number Finder</h2>
    <form method="GET" action="{{ route('reports.serial_number_finder') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search serial, number, title, ref..." value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Requisition Number</th>
                <th>Title</th>
                <th>Reference</th>
                <th>Added By</th>
                <th>Date Added</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requisitions as $req)
                <tr>
                    <td>{{ $loop->iteration + ($requisitions->currentPage() - 1) * $requisitions->perPage() }}</td>
                    <td>{{ $req->req_number }}</td>
                    <td>{{ $req->req_title }}</td>
                    <td>{{ $req->req_ref }}</td>
                    <td>{{ $req->req_added_by }}</td>
                    <td>{{ $req->req_date_added }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No requisitions found.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $requisitions->withQueryString()->links() }}
</div>
@endsection 