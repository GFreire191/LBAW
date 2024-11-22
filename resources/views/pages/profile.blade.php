@extends('layouts.app')

@section('content')
<body>
    <div class="container-profile">

        <div class="top-div-profile">

            <div class="top-left-profile">

                <div class="circle-profile">
                    <img src="{{ asset('storage/profile_pictures/' . $user->profile_picture_id) }}" alt="{{$user->username}}">
                </div>

            </div>

            <div class="top-right-profile">

                <p>{{$user->username}}</p>
                <p>{{$user->bio}}</p>
                
                @if(Auth::user()->id===$user->id)
                    <a href="{{ route('profile.create') }}" data-user = "{{$user->id}}" class="#" id="editProfile" >Edit Profile</a>
                @endif


            </div>

        </div>

        <div class="bottom-div-profile">

            <div class="bottom-left-profile">

                <h2 class="center">Questions</h2>

                @each('partials.question', $questions, 'question')

            </div>

            <div class="bottom-right-profile">

                <h2 class="center">Answers</h2>

                @each('partials.answer', $answers, 'answer')

            </div>

        </div>

    </div>
</body>

@endsection
