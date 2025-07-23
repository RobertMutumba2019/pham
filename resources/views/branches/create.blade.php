@extends('layouts.app')

@section('title', 'Add Branch')

@section('content')
<div class="container">
    <h2>Add Branch</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('branches.store') }}">
        @csrf
        <div class="mb-3">
            <label for="branch_name" class="form-label">Branch Name</label>
            <input type="text" class="form-control" id="branch_name" name="branch_name" value="{{ old('branch_name') }}" required>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-success">Add Branch</button>
            <a href="{{ route('branches.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection 