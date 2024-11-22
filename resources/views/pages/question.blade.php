@extends('layouts.app')

@section('content')

    @include('partials.question', ['question' => $question])

@can('create' , App\Models\Answer::class)

<div id="AnswerFormContainer"  >
    @include('partials.create_answer')

    
</div>

@endcan



    <h2 class="center">Answers</h2>

    <div id="answersContainer">

        @each('partials.answer', $answers, 'answer')

    </div>



@endsection
