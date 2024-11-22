@foreach ($comments as $comment)
    @include('partials.comment', ['comment' => $comment])
@endforeach