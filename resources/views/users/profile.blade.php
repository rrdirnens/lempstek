@extends('layout')
@push('head')

@endpush

@section('content')

<section class="container mx-auto p-4">

    <h1>Hi, {{$user->name}} (id: {{$user->id}})</h1>
    
    <br>

    @if(session()->has('message'))
    	<h1 class="text-green-400">{{session('message')}}</h1>
	@endif
   
    @error('user')
		<p class="text-red-500 text-xs mt-1">{{$message}}</p>
	@enderror
	
    <div class="mb-4">
        <h2 class="mb-4">MOVIES</h2>
        @foreach ($movies as $movie)
            <a class="mx-2 p-2 border hover:bg-red-200 inline block" href="{{route('users.movie.delete', $movie->movie_id)}}">
                <strong>{{$movie->movie_id}}</strong>
            </a>
        @endforeach
    </div>

    <div class="mb-4">
        <h2 class="mb-4">SHOWS</h2>
        @foreach ($shows as $show)
            <a class="mx-2 p-2 border hover:bg-red-200 inline-block" href="{{route('users.tv.delete', $show->show_id)}}">
                <strong>{{$show->show_id}}</strong>
            </a>
        @endforeach
    </div>

    <div class="mb-4">
        <h2 class="mb-4">YER FOOKING SCHEDULE</h2>
        {{-- calendar made from $dates --}}
        @foreach ($dates as $date)
           <div>{{$date['date']}}</div>
        @endforeach

    </div>
</section>
@endsection
