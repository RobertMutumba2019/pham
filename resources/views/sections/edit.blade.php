@extends('layouts.app')

@section('title', 'Edit Section')

@section('content')
<div class="container">
    <h2>Edit Section</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('sections.update', $section->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="section_name" class="form-label">Section Name</label>
            <input type="text" class="form-control" id="section_name" name="section_name" value="{{ old('section_name', $section->section_name) }}" required>
        </div>
        <div class="mb-3">
            <label for="section_dept_id" class="form-label">Department</label>
            <select class="form-control" id="section_dept_id" name="section_dept_id" required>
                <option value="">Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ (old('section_dept_id', $section->section_dept_id) == $department->id) ? 'selected' : '' }}>{{ $department->dept_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update Section</button>
            <a href="{{ route('sections.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection 