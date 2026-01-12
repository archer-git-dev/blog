@extends('layout.app')

@section('title', 'Create Post')

@section('content')
    <div class="form-container">
        <h2>Create New Post</h2>

        <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       placeholder="Enter post title">
                @error('title')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="6" required
                          placeholder="Enter post description">{{ old('description') }}</textarea>
                @error('description')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Tags</label>
                <div class="tags-container">
                    @foreach($tags as $tag)
                        <label class="tag-checkbox">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}">
                            <span class="tag-label">{{ $tag->title }}</span>
                        </label>
                    @endforeach
                </div>
                @error('tags')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" accept="image/*">
                @error('image')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Create Post</button>
                <a href="{{ route('post.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
