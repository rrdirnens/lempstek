@extends('layout')


@section('content')

<section class="container mx-auto p-4">

    @if (isset($show))
        @php
            // dd($show);
        @endphp

        <p class="text-xl mb-2">Title: {{$show['original_name']}}</p>
        <p class="mb-4">Release date : {{$show['first_air_date']}}</p>
        @if ($show['next_episode_to_air'])
            <p class="mb-4">Next episode : {{$show['next_episode_to_air']['air_date']}}, in {{$show['next_release_calc']}} </p>
        @else
            <p class="mb-4">Next episode : no info :(</p>        
        @endif
        <p class="italic mb-2">Overview : {{$show['overview']}}</p>
        <img src="https://image.tmdb.org/t/p/w500{{ $show['backdrop_path'] }}" alt="backdrop" class="mb-2">
        <img src="https://image.tmdb.org/t/p/w500{{ $show['poster_path'] }}" alt="poster">

    @endif
</section>

@endsection