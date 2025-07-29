@extends('layouts.app')

@section('title', 'User Rights & Privileges')

@section('content')
<div class="container">
    <h2>User Rights & Privileges</h2>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td>{{ $role->ur_name }}</td>
                <td>
                    @if(auth()->check() && auth()->user()->role && in_array(strtolower(auth()->user()->role->ur_name), ['admin', 'administrator', 'supervisor']))
                        <a href="{{ route('access-rights.edit', $role->id) }}" class="btn btn-sm btn-primary">Edit Privileges</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 