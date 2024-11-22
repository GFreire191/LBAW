@foreach ($questions as $question)
    @include('partials.question', ['question' => $question])
@endforeach