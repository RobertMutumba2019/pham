@extends('layouts.app')

@section('title', 'System Users')

@section('content')
<div class="container">
    <h2>System Users</h2>
    <div class="mb-3">
        <a href="{{ route('users.create') }}" class="btn btn-success">Add User</a>
    </div>
    <form method="GET" class="mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="rowsPerPage" class="form-control" onchange="this.form.submit()">
                    @foreach([20, 50, 100, 200] as $size)
                        <option value="{{ $size }}" {{ request('rowsPerPage', 20) == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Surname</th>
                <th>Other Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->user_surname }}</td>
                <td>{{ $user->user_othername }}</td>
                <td>{{ $user->user_name }}</td>
                <td>{{ $user->user_email }}</td>
                <td>{{ $user->user_active ? 'Active' : 'Locked' }}</td>
                <td>{{ $user->role ? $user->role->ur_name : '' }}</td>
                <td>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->withQueryString()->links() }}
</div>
@endsection 