@extends('layouts.app')

@section('title', 'Add Designation')

@section('content')
<div class="container">
    <h2>Add Designation</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('designations.store') }}">
        @csrf
        <div class="mb-3">
            <label for="designation_name" class="form-label">Designation Name</label>
            <input type="text" class="form-control" id="designation_name" name="designation_name" value="{{ old('designation_name') }}" required>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-success">Add Designation</button>
            <a href="{{ route('designations.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection 