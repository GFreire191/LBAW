

@extends('layouts.app')


@section('content')

@include('partials.sidebar', ['page' => 'admin'])

<div class="admin-content-container">
    @foreach ($reports as $report)
        
            @include('partials.report', ['report' => $report])
        
    @endforeach
</div>

@endsection