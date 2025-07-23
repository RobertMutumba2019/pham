@extends('layouts.app')

@section('title', 'Approval Matrices')

@section('content')
<div class="container">
    <h2>Approval Matrices</h2>
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
            @forelse($matrices as $matrix)
            <tr>
                <td>{{ $matrix->id }}</td>
                <td>{{ $matrix->name ?? '-' }}</td>
                <td>{{ $matrix->created_at }}</td>
                <td>
                    <!-- Add Edit/Delete buttons as needed -->
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No approval matrices found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 