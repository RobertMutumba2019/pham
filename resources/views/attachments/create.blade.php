@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Upload New Attachment</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('attachments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="file" class="form-label">Choose File</label>
            <input type="file" name="file" id="file" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="attachable_type" class="form-label">Attachable Type</label>
            <input type="text" name="attachable_type" id="attachable_type"
                   class="form-control" placeholder="e.g., App\Models\Post" required>
        </div>

        <div class="mb-3">
            <label for="attachable_id" class="form-label">Attachable ID</label>
            <input type="number" name="attachable_id" id="attachable_id"
                   class="form-control" placeholder="e.g., 1" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description (Optional)</label>
            <textarea name="description" id="description" rows="3"
                      class="form-control"></textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="is_public" id="is_public" class="form-check-input" value="1">
            <label class="form-check-label" for="is_public">Make Public</label>
        </div>

        <button type="submit" class="btn btn-primary">Upload Attachment</button>
        <a href="{{ route('attachments.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
