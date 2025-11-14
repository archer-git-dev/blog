@extends('layout.app')

@section('title', 'Register')

@section('content')
    <div class="form-container">
        <h2>Create Your Account</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       placeholder="Enter your full name">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required
                       placeholder="Create a password">
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required
                       placeholder="Repeat your password">
            </div>

            <button type="submit" class="btn">Create Account</button>
        </form>

        <p class="text-center mt-3">
            Already have an account?
            <a href="{{ route('login') }}" class="text-link">Sign in here</a>
        </p>
    </div>
@endsection
