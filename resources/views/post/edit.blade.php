@extends('layout.app')

@section('title', 'Edit Post')

@section('content')
    <div class="form-container">
        <h2>Edit Post</h2>

        <form method="POST" action="{{ route('post.update', $post) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                       placeholder="Enter post title">
                @error('title')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="6" required
                          placeholder="Enter post description">{{ old('description', $post->description) }}</textarea>
                @error('description')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Tags</label>
                <div class="tags-container">
                    @foreach($tags as $tag)
                        <label class="tag-checkbox">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                {{ in_array($tag->id, $post->tags->pluck('id')->toArray()) ? 'checked' : '' }}>
                            <span class="tag-label">{{ $tag->title }}</span>
                        </label>
                    @endforeach
                </div>
                @error('tags')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Current Image</label>
                @if($post->img_src)
                    <div class="current-image">
                        <img src="{{ Storage::url($post->img_src) }}" alt="Current image">
                        <p class="image-note">Current image</p>
                    </div>
                @else
                    <p class="no-image">No image uploaded</p>
                @endif

                <label>New Image (optional)</label>
                <input type="file" name="image" accept="image/*">
                @error('image')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Update Post</button>
                <a href="{{ route('post.show', $post) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
