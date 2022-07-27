@extends('layout')


@section('content')

<section class="container mx-auto p-4">

    @if (isset($movie))
        @php
            // dd($movie);
        @endphp

        <p class="text-xl mb-2">Title: {{$movie['original_title']}}</p>
        <p class="mb-4">Release date : {{$movie['release_date']}}</p>
        <p class="italic">Overview : {{$movie['overview']}}</p>
    @endif
</section>

@endsection