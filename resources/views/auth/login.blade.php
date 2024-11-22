@extends('layouts.app')

@section('content')

<div class="login-form-container">

    <form method="POST" action="{{ route('login') }}" class="login-form">
        <div class="align-with-buttons">
            {{ csrf_field() }}

            <label for="email">E-mail:</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="input-fields" required autofocus>
            @if ($errors->has('email'))
                <span class="error">
                {{ $errors->first('email') }}
                </span>
            @endif

            <label for="password" >Password:</label>
            <input id="password" type="password" name="password" class="input-fields" required>
            @if ($errors->has('password'))
                <span class="error">
                    {{ $errors->first('password') }}
                </span>
            @endif

            <label>
                Remember Me <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            </label>
        </div>
        
        <div class="login-buttons">
            <button type="submit" class="question-button loginButton">
                Login
            </button>
            <h3>Still Don't Have an Account?</h3>
            <a class="question-button registerButton" href="{{ route('register') }}">Register</a>
        </div>
        
        @if (session('success'))
            <p class="success">
                {{ session('success') }}
            </p>
        @endif
    </form>
</div>
@endsection