@extends('layouts.app')

@section('title', 'Sections')

@section('content')
<div class="container">
    <h2>Sections</h2>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <div class="mb-3">
        <a href="{{ route('sections.create') }}" class="btn btn-success">Add Section</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sections as $section)
            <tr>
                <td>{{ $section->section_name }}</td>
                <td>{{ $section->department ? $section->department->dept_name : '' }}</td>
                <td>
                    <a href="{{ route('sections.edit', $section->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('sections.destroy', $section->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this section?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $sections->links() }}
</div>
@endsection 