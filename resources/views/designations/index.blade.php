@extends('layouts.app')

@section('title', 'Designations')

@section('content')
<div class="container">
    <h2>Designations</h2>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <div class="mb-3">
        @if(auth()->check() && auth()->user()->role && in_array(strtolower(auth()->user()->role->ur_name), ['admin', 'administrator', 'supervisor']))
            <a href="{{ route('designations.create') }}" class="btn btn-success">Add Designation</a>
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
            @foreach($designations as $designation)
            <tr>
                <td>{{ $designation->designation_name }}</td>
                <td>{{ $designation->designation_added_by }}</td>
                <td>{{ $designation->designation_date_added }}</td>
                <td>
                    @if(auth()->check() && auth()->user()->role && in_array(strtolower(auth()->user()->role->ur_name), ['admin', 'administrator', 'supervisor']))
                        <a href="{{ route('designations.edit', $designation->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('designations.destroy', $designation->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this designation?')">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $designations->links() }}
</div>
@endsection 