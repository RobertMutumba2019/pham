@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Summary List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($summary as $row)
                <tr>
                    <td>{{ $row->req_status }}</td>
                    <td>{{ $row->total }}</td>
                </tr>
            @empty
                <tr><td colspan="2">No data found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 

@section('content')
<div class="container">
    <h2>Summary List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($summary as $row)
                <tr>
                    <td>{{ $row->req_status }}</td>
                    <td>{{ $row->total }}</td>
                </tr>
            @empty
                <tr><td colspan="2">No data found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 