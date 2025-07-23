@extends('layouts.app')

@section('title', 'Edit User Role')

@section('content')
<div class="container">
    <h2>Edit User Role</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('user-roles.update', $role->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="ur_name" class="form-label">Role Name</label>
            <input type="text" class="form-control" id="ur_name" name="ur_name" value="{{ old('ur_name', $role->ur_name) }}" required>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update Role</button>
            <a href="{{ route('user-roles.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection 