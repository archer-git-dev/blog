@extends('layout.app')

@section('title', 'Edit Comment')

@section('content')
    <div class="form-container">
        <h2>Статистика по блогу</h2>

        <div style="display: flex; justify-content: space-between;">
            <div>Количество постов:<br/> {{ $stats['total_posts'] }}</div>
            <div>Количество комментариев:<br/> {{ $stats['total_comments'] }}</div>
            <div>Самый популярный пост:<br/> {{ $stats['tot_post_title'] }}</div>
        </div>
    </div>
@endsection
