@extends('layout.app')

@section('title', 'Verify Email')

@section('content')
    <p>Необходимо подтверждение e-mail</p>
    <a href="{{ route('verification.send') }}">
        Отправить повторно
    </a>
@endsection
