@extends('layout')


@section('content')

<section class="container mx-auto p-4">
    
    @if (isset($movie))

        <p class="text-xl mb-2">Title: {{$movie['original_title']}}</p>
        <p class="mb-4">Release date : {{$movie['release_date']}}</p>
        <p class="italic">Overview : {{$movie['overview']}}</p>
        <a href="{{ route('users.movie.store', $id = $movie['id']) }}" class="font-bold my-4">Add to calendar</a>

        <img src="https://image.tmdb.org/t/p/w500{{ $movie['backdrop_path'] }}" alt="backdrop" class="mb-2">
        <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="poster">
    @endif
</section>

@endsection