@extends('layout.app')

@section('title', 'Login')

@section('content')
    <div class="form-container">
        <h2>Login to Your Account</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required
                       placeholder="Enter your password">
            </div>

            <button type="submit" class="btn">Sign In</button>
        </form>

        <p class="text-center mt-3">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-link">Create one here</a>
        </p>
    </div>
@endsection
