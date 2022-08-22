@extends('layout')

@section('content')
	
<section class="container mx-auto p-4">

	@if(session()->has('message'))
	<h1 class="text-green-400 text-xl">{{session('message')}}</h1>
	@endif
	
	<form action="/" method="POST" class="h-16 flex justify-center">
		@csrf
		<input type="text" name="search_query" placeholder="Enter keywords" required class="border p-2 h-full">
		<button type="submit" class="p-2 w-32 h-full text-lg bg-blue-200">Search</button>
	</form>

	@error('user_show')
		<p class="text-red-500 text-xl mt-1">{{$message}}</p>
	@enderror
	
	@error('user_movie')
		<p class="text-red-500 text-xl mt-1">{{$message}}</p>
	@enderror
	
	@if (isset($search_results))
		<div class="flex justify-center">
			@if ($search_results->tv)
			<div class="mr-4">
				<h1>TV shows</h1>
				@foreach ($search_results->tv as $item)
					<div class="border p-2 mb-1">

						<a href="{{route('users.tv.store', $item->id)}}" class="inline-block mb-2 hover:text-green-900">Add to calendar</a>
						<a href="{{route('shows.show', $item->id)}}">
							<div>
								<strong>{{$item->name}}</strong>
							</div>
						</a>
					</div>
				@endforeach
			</div>
			@endif
			@if ($search_results->movies)
			<div class="">
				<h1>Movies</h1>
				@foreach ($search_results->movies as $item)
					<div class="border p-2 mb-1">
						<a href="{{route('users.movie.store', $item->id)}}" class="inline-block mb-2 hover:text-green-900">Add to calendar</a>
						<a href="{{route('movies.show', $item->id)}}">
							<div>
								<strong>{{$item->title}}</strong>
							</div>
						</a>
					</div>
				@endforeach
			</div>
			@endif
		</div>
	@endif
	
	<div>{{ isset($search_msg) ? $search_msg : '' }}</div>
	
</section>

@endsection