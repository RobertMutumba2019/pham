@extends('layouts.app')

@section('title', 'Delegations')

@section('content')
<div class="container">
    <h2>Delegations</h2>
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($delegations as $delegation)
            <tr>
                <td>{{ $delegation->id }}</td>
                <td>{{ $delegation->name ?? '-' }}</td>
                <td>{{ $delegation->created_at }}</td>
                <td>
                    <!-- Add Edit/Delete buttons as needed -->
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No delegations found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 