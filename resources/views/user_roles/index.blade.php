@extends('layouts.app')

@section('title', 'User Roles')

@section('content')
<div class="container">
    <h2>User Roles</h2>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <div class="mb-3">
        @if(auth()->check() && in_array(strtolower(auth()->user()->role->ur_name), ['admin', 'administrator', 'supervisor']))
            <a href="{{ route('user-roles.create') }}" class="btn btn-success">Add Role</a>
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
            @foreach($roles as $role)
            <tr>
                <td>{{ $role->ur_name }}</td>
                <td>{{ $role->ur_added_by }}</td>
                <td>{{ $role->ur_date_added }}</td>
                <td>
                    @if(auth()->check() && in_array(strtolower(auth()->user()->role->ur_name), ['admin', 'administrator', 'supervisor']))
                        <a href="{{ route('user-roles.edit', $role->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('user-roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this role?')">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $roles->links() }}
</div>
@endsection 