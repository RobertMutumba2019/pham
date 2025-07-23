@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container">
    <h2>Edit User</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-4">
                <label for="user_surname" class="form-label">Surname</label>
                <input type="text" class="form-control" id="user_surname" name="user_surname" value="{{ old('user_surname', $user->user_surname) }}" required>
            </div>
            <div class="col-md-4">
                <label for="user_othername" class="form-label">Other Name</label>
                <input type="text" class="form-control" id="user_othername" name="user_othername" value="{{ old('user_othername', $user->user_othername) }}" required>
            </div>
            <div class="col-md-4">
                <label for="user_email" class="form-label">Email</label>
                <input type="email" class="form-control" id="user_email" name="user_email" value="{{ old('user_email', $user->user_email) }}" required>
            </div>
            <div class="col-md-4">
                <label for="user_telephone" class="form-label">Telephone</label>
                <input type="text" class="form-control" id="user_telephone" name="user_telephone" value="{{ old('user_telephone', $user->user_telephone) }}">
            </div>
            <div class="col-md-4">
                <label for="user_gender" class="form-label">Gender</label>
                <select class="form-control" id="user_gender" name="user_gender" required>
                    <option value="">--Select--</option>
                    <option value="M" {{ old('user_gender', $user->user_gender) == 'M' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ old('user_gender', $user->user_gender) == 'F' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="user_role" class="form-label">User Role</label>
                <select class="form-control" id="user_role" name="user_role" required>
                    <option value="">--Select--</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('user_role', $user->user_role) == $role->id ? 'selected' : '' }}>{{ $role->ur_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection 