@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <h2 class="mb-4">Welcome, {{ Auth::user()->user_surname }} (Admin)</h2>
    <div class="row g-3">
        <div class="col-md-4">
            <a href="{{ route('users.index') }}" class="card card-body text-center shadow-sm">Manage Users</a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('user-roles.index') }}" class="card card-body text-center shadow-sm">User Roles</a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('access-rights.index') }}" class="card card-body text-center shadow-sm">User Rights & Privileges</a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('designations.index') }}" class="card card-body text-center shadow-sm">Designations</a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('branches.index') }}" class="card card-body text-center shadow-sm">Branches</a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('departments.index') }}" class="card card-body text-center shadow-sm">Departments</a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('approval-matrices.index') }}" class="card card-body text-center shadow-sm">Approval Matrix</a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('approval-groups.index') }}" class="card card-body text-center shadow-sm">Approval Groups</a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('delegations.index') }}" class="card card-body text-center shadow-sm">Delegation</a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('users.edit', Auth::id()) }}" class="card card-body text-center shadow-sm">Account Settings</a>
        </div>
    </div>
</div>
@endsection 