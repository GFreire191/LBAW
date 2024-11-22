@extends('layouts.app')

@section('content')
<div class="login-form-container" id="registerContainer">
  <form method="POST" action="{{ route('register') }}" class="login-form" enctype="multipart/form-data">
    <div class="align-with-buttons">
      
        {{ csrf_field() }}

        <label for="name">Username:</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" class="input-fields register" required autofocus>
        @if ($errors->has('name'))
          <span class="error">
              {{ $errors->first('name') }}
          </span>
        @endif

        <label for="email">E-Mail Address:</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" class="input-fields register" required>
        @if ($errors->has('email'))
          <span class="error">
              {{ $errors->first('email') }}
          </span>
        @endif

        <label for="bio"> Bio:</label>
        <textarea id="bio" name="bio" class="text-area-field input-fields register" sometimes></textarea>

        <label for="password">Password:</label>
        <input id="password" type="password" name="password" class="input-fields register" required>
        @if ($errors->has('password'))
          <span class="error">
              {{ $errors->first('password') }}
          </span>
        @endif

        <label for="password-confirm">Confirm Password:</label>
        <input id="password-confirm" type="password" name="password_confirmation" class ="input-fields register" required>
    </div>

    
      <div class="circle-profile" id="bigCircleProfile">
        <img id="profile-preview" src="{{ asset('storage/profile_pictures/user_default.jpg') }}">
      </div>

      <div class="upload-btn-wrapper">
        <button class="question-button" id="upLoadButton">Upload a profile picture</button>
        <input id="profile-upload" type="file" name="profile_picture_id" accept="image/*">
      </div>
    

    <div class="login-buttons">
      <button type="submit" class="question-button registerButton" id="pageRegisterButton">
        Register
      </button>
      <h3 id="account-register-text">Already Have an Account?</h3>
      <a class="question-button loginButton" href="{{ route('login') }}">Login</a>
    </div>
  </form>
</div>
@endsection