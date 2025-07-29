@extends('layouts.app')

@section('title', 'Departments')

@section('content')
<div class="container">
    <h2>Departments</h2>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <div class="mb-3">
        @if(auth()->check() && auth()->user()->role && in_array(strtolower(auth()->user()->role->ur_name), ['admin', 'administrator', 'supervisor']))
            <a href="{{ route('departments.create') }}" class="btn btn-success">Add Department</a>
        @endif
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
            @foreach($departments as $department)
            <tr>
                <td>{{ $department->dept_name }}</td>
                <td>{{ $department->dept_added_by }}</td>
                <td>{{ $department->dept_date_added }}</td>
                <td>
                    @if(auth()->check() && auth()->user()->role && in_array(strtolower(auth()->user()->role->ur_name), ['admin', 'administrator', 'supervisor']))
                        <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('departments.destroy', $department->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this department?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $departments->links() }}
</div>
@endsection 