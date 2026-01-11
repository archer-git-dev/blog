@extends('layout.app')

@section('title', 'Edit Comment')

@section('content')
    <div class="form-container">
        <h2>Edit Comment</h2>

        <form method="POST" action="{{ route('comment.update', [$comment->id]) }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="post_id" value="{{ $comment->post_id }}">

            <div class="form-group">
                <label>Your Comment</label>
                <textarea name="text" rows="4" required
                          placeholder="Write your comment...">{{ old('text', $comment->text) }}</textarea>
                @error('text')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Update Comment</button>
                <a href="{{ route('post.show', $comment->post_id) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
