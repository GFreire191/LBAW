@extends('layouts.app')

@section('content')
    <h1>Search Results</h1>

    @if(count($questions) > 0)
        
            @each('partials.question', $questions, 'question')
        
    @else
    <h2>No Questions were found matching your search </h2>
    @endif
@endsection