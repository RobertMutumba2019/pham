@extends('layouts.app')

@section('title', 'Add Department')

@section('content')
<div class="container">
    <h2>Add Department</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('departments.store') }}">
        @csrf
        <div class="mb-3">
            <label for="dept_name" class="form-label">Department Name</label>
            <input type="text" class="form-control" id="dept_name" name="dept_name" value="{{ old('dept_name') }}" required>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-success">Add Department</button>
            <a href="{{ route('departments.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection 