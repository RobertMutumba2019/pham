@extends('layouts.app')

@section('title', 'Branches')

@section('content')
<div class="container">
    <h2>Branches</h2>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <div class="mb-3">
        <a href="{{ route('branches.create') }}" class="btn btn-success">Add Branch</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Added By</th>
                <th>Date Added</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($branches as $branch)
            <tr>
                <td>{{ $branch->branch_name }}</td>
                <td>{{ $branch->branch_added_by }}</td>
                <td>{{ $branch->branch_date_added }}</td>
                <td>
                    <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this branch?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $branches->links() }}
</div>
@endsection 