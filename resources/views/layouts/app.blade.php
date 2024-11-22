<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/questionPartial.css') }}">
    <link rel="stylesheet" href="{{ asset('css/answerPartial.css') }}">
    <link rel="stylesheet" href="{{ asset('css/commentPartial.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tags.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layouts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/questions.js') }}"></script>
    <script src="{{ asset('js/tagMenu.js') }}"></script>
    <script src="{{ asset('js/answers.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/questionPage.js') }}"></script>
    <script src="{{ asset('js/report.js') }}"></script>
    <script src="{{ asset('js/comment.js') }}"></script>
    <script src="{{ asset('js/votes.js') }}"></script>
    <script src="{{ asset('js/profilePage.js') }}"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">

</head>
<body>
    <div class="top-navbar">
        <a href="{{ url('/home') }}" class="logo">StackUnderFlow</a>
        <div class="circle">
            <img src="{{ url('images/PaPa.png') }}" alt="StackUnderFlow">
        </div>
        <a type="button" class="button" style="padding-left: 10px" href="/top_questions" >TOPics</a>
        <a type="button" class="button" style="padding-right: 10px; padding-right: 10px " href="/about_us" >About Us</a>
        <div class="search-bar">
            <!-- Search form goes here -->
        </div>
        <div class="navbar-actions">
        <form class="search-bar" action="/search" method="get">

            <input type="text" name="query" placeholder="Search..." class="search-input">
            <button type="submit" class="search-btn">Search</button>

        </form>
            @auth
                <a type="button" class="button" href="{{ route('profile.show', ['id' => Auth::user()->id]) }}" >{{ Auth::user()->username }}</a>
                
                @if( Auth::check() && (Auth::user()->is_admin || Auth::user()->is_moderator))
                    <a type="button" class="button" href= "{{route('admin.reports')}}" >Manage</a>
                @endif
                <a type="button" class="button" id="notiButton" href="{{ route('show.notification', ['user' => Auth::user()]) }}" data-user-id = "{{ Auth::user()->id }}" action="{{ route('show.notification', ['user' => Auth::user()]) }}" >Notifications</a>
                <a type="button" class="button" href="/logout"> Logout </a>
            @else
                <a type="button" class="button" href="/login" class="btn btn-primary">Log In</a>
                <a type="button" class="button" href="/register" class="btn btn-secondary">Register</a>
            @endauth
        </div>
    </div>

    <div class="container">
        @yield('content')
        @auth
        <div id="createFormContainer">
        
        </div>
        <a type ="button" class ="question-button" id="FormButton" href="/question/create" id="load-question-form">Create Question</a>
        @endauth
    </div>
    <script src="{{ asset('js/logReg.js') }}"></script>
</body>
</html>