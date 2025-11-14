@extends('layout.app')

@section('title', $post->title)

@section('content')
    <div class="posts-container">
        <!-- Пост -->
        <div class="post-full">
            @if($post->img_src)
                <img src="{{ Storage::url($post->img_src) }}" alt="{{ $post->title }}" class="post-full-image">
            @endif

            <div class="post-full-content">
                <h1>{{ $post->title }}</h1>
                <p class="post-meta">By: {{ $post->user->name }} • {{ $post->created_at->format('M d, Y') }}</p>

                <div class="post-full-description">
                    {{ $post->description }}
                </div>

                @if($post->tags->count() > 0)
                    <div class="post-tags">
                        <strong>Tags:</strong>
                        @foreach($post->tags as $tag)
                            <span class="tag">{{ $tag->title }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="post-actions">
                    <a href="{{ route('post.index') }}" class="btn btn-secondary">Back to Posts</a>
                    @can('update', $post)
                        <a href="{{ route('post.edit', $post) }}" class="btn">Edit Post</a>
                        <form action="{{ route('post.destroy', $post) }}" method="POST" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure?')">Delete Post</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Комментарии -->
        <div class="comments-section">
            <h3>Comments ({{ $post->comments->count() }})</h3>

            <!-- Форма добавления комментария -->
            @auth
                <div class="comment-form-container">
                    <form method="POST" action="{{ route('comment.store') }}">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">

                        <div class="form-group">
                            <label>Add a comment</label>
                            <textarea name="text" rows="3" required
                                      placeholder="Write your comment...">{{ old('text') }}</textarea>
                            @error('text')
                            <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn">Post Comment</button>
                    </form>
                </div>
            @else
                <p class="login-prompt">
                    <a href="{{ route('login') }}">Log in</a> to leave a comment.
                </p>
            @endauth

            <!-- Список комментариев -->
            <div class="comments-list">
                @forelse($post->comments as $comment)
                    <div class="comment-card">
                        <div class="comment-header">
                            <strong>{{ $comment->user->name }}</strong>
                            <span class="comment-date">{{ $comment->created_at->format('M d, Y H:i') }}</span>
                        </div>

                        <div class="comment-text">
                            {{ $comment->text }}
                        </div>

                        @can('update', $comment)
                            <div class="comment-actions">
                                <a href="{{ route('comment.edit', $comment) }}" class="btn btn-sm">Edit</a>
                                <form action="{{ route('comment.destroy', $comment) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this comment?')">Delete</button>
                                </form>
                            </div>
                        @endcan
                    </div>
                @empty
                    <p class="no-comments">No comments yet. Be the first to comment!</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
