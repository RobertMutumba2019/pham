@extends('layouts.app')

@section('title', 'Approval Groups')

@section('content')
<div class="container">
    <h2>Approval Groups</h2>
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
            @forelse($groups as $group)
            <tr>
                <td>{{ $group->id }}</td>
                <td>{{ $group->name ?? '-' }}</td>
                <td>{{ $group->created_at }}</td>
                <td>
                    <!-- Add Edit/Delete buttons as needed -->
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No approval groups found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 