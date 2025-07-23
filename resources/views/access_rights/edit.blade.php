@extends('layouts.app')

@section('title', 'Edit Privileges for ' . $role->ur_name)

@section('content')
<div class="container">
    <h2>Edit Privileges for {{ $role->ur_name }}</h2>
    <form method="POST" action="{{ route('access-rights.update', $role->id) }}">
        @csrf
        @method('PUT')
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Page</th>
                    @foreach($controls as $key => $label)
                        <th>{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($pages as $page)
                <tr>
                    <td>{{ $page }}</td>
                    @foreach($controls as $key => $label)
                        <td>
                            <input type="checkbox" name="pages[{{ $page }}][{{ $key }}]" value="1"
                                {{ isset($accessRights[$page]) && $accessRights[$page]->$key ? 'checked' : '' }}>
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            <button type="submit" class="btn btn-success">Save Privileges</button>
            <a href="{{ route('access-rights.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection 