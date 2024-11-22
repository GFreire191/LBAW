@foreach ($notifications as $notification)
    @include('partials.notification', ['nofitication' => $notification])
@endforeach