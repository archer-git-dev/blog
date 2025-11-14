@extends('layout.app')

@section('title', 'All Posts')

@section('content')
    <div class="posts-container">
        <div class="header-actions">
            <h2>All Posts</h2>
            <a href="{{ route('post.create') }}" class="btn">Create New Post</a>
        </div>

        <div class="posts-grid">
            @foreach($posts as $post)
                <div class="post-card">
                    @if($post->img_src)
                        <img src="{{ Storage::url($post->img_src) }}" alt="{{ $post->title }}" class="post-image">
                    @endif
                    <div class="post-content">
                        <h3>
                            <a href="{{ route('post.show', $post->id) }}" class="post-title-link">
                                {{ $post->title }}
                            </a>
                        </h3>
                        <p class="post-description">{{ Str::limit($post->description, 100) }}</p>
                        <p class="post-author">By: {{ $post->user->name }} • {{ $post->created_at->format('M d, Y') }}</p>

                        @if($post->tags->count() > 0)
                            <div class="post-tags">
                                @foreach($post->tags as $tag)
                                    <span class="tag">{{ $tag->title }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="post-actions">
                            <a href="{{ route('post.show', $post->id) }}" class="btn btn-sm">Read More</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($posts->isEmpty())
            <div class="empty-state">
                <p>No posts yet. <a href="{{ route('post.create') }}">Create the first one!</a></p>
            </div>
        @endif
    </div>
@endsection
