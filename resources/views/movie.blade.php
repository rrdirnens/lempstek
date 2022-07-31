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

        <img src="https://image.tmdb.org/t/p/w500{{ $movie['backdrop_path'] }}" alt="backdrop" class="mb-2">
        <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="poster">
    @endif
</section>

@endsection