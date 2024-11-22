@extends('layouts.app')

@section('content')


   

@include('partials.sideBar', ['tags' => $tags, 'page' => 'home'])




<!-- create div for questions -->
<div id="questionsContainer" data-page="1">
    @each('partials.question', $questions, 'question')
</div>


@endsection




