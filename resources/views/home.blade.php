@extends('layout')

@section('content')
	
<section class="container mx-auto p-4">
	
	<form action="{{ route('search') }}" method="GET" class="h-16 flex justify-center">
		<input type="text" name="search_query" placeholder="Enter keywords" required class="border p-2 h-full">
		<button type="submit" class="p-2 w-32 h-full text-lg bg-blue-200">Search</button>
	</form>

	@isset($search_results)
		<form action="{{ route('search') }}" method="GET" class="flex justify-center">
			@for ($i = 0; $i < $search_pagination_total; $i++)
				<button type="submit" name="page" value="{{$i+1}}" class="p-2 w-32 h-full text-lg {{$search_pagination_current == $i+1 ? 'bg-blue-400' : 'bg-blue-200'}}">{{$i+1}}</button>
			@endfor
			<input type="text" name="search_query" class="hidden" value="{{$search_query}}">
		</form>
	@endisset

	@if (isset($search_results))
		<div class="flex justify-center">
			@if ($search_results->tv)
			<div class="mr-4">
				<h1>TV shows</h1>
				@foreach ($search_results->tv as $item)
					<div class="border p-2 mb-1">

						@if (isset($item->in_calendar) && $item->in_calendar)
							<a href="{{route('users.tv.delete', $item->id)}}" class="inline-block mb-2 hover:text-red-900 text-red-500">REMOVE</a>
						@else
							<a href="{{route('users.tv.store', $item->id)}}" class="inline-block mb-2 hover:text-green-900">Add to calendar</a>
						@endif
						<a href="{{route('tv.show', $item->id)}}">
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
						@if (isset($item->in_calendar) && $item->in_calendar)
							<a href="{{route('users.movie.delete', $item->id)}}" class="inline-block mb-2 hover:text-red-900 text-red-500">REMOVE</a>
						@else
							<a href="{{route('users.movie.store', $item->id)}}" class="inline-block mb-2 hover:text-green-900">Add to calendar</a>
						@endif
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

@if (isset($logged_in) && $logged_in)
	<section class="container mx-auto p-4">
		<div class="mb-4">
			<h2 class="mb-4">MOVIES</h2>
			<div class="flex flex-wrap">
				@if (isset($movies) && $movies)
					@foreach ($movies as $movie)
					<div class="item-button">
						<div class="item-button__container">
							{{$movie['title']}}
						</div>
						<div class="item-button__overlay">
							<a class="item-button__action item-button__action--view" href="{{route('movies.show', $movie['id'])}}">View</a>
							<div class="item-button__divider">|</div>
							<a class="item-button__action item-button__action--remove" href="{{route('users.movie.delete', $movie['id'])}}">Remove</a>
						</div>
					</div>
					@endforeach
				@endif
			</div>
		</div>
		
		<div class="mb-4">
			<h2 class="mb-4">SHOWS</h2>
			<div class="flex flex-wrap">
				@if (isset($shows) && $shows)
					@foreach ($shows as $show)
						<div class="item-button">
							<div class="item-button__container">
								{{$show['name']}}
							</div>
							<div class="item-button__overlay">
								<a class="item-button__action item-button__action--view" href="{{route('tv.show', $show['id'])}}">View</a>
								<div class="item-button__divider">|</div>
								<a class="item-button__action item-button__action--remove" href="{{route('users.tv.delete', $show['id'])}}">Remove</a>
							</div>
						</div>
					@endforeach
				@endif
			</div>
		</div>
		
		<div class="mb-4">
			<h2 class="mb-4">YER FOOKING SCHEDULE</h2>        
			<div class="flex overflow-x-auto">
				@if (isset($dates) && $dates)
					
				@foreach ($dates as $date => $items)
					<div class="min-w-[20%] w-1/5 m-2 p-2 {{ $items[0]['is_today'] ?'bg-blue-400' : 'bg-blue-200' }}">
						<div class="mb-2">{{$date}} @if ($items[0]['is_today']) | <span class="font-bold">TODAY</span> @endif</div>
						@if ($items[0]['days_left'] >= 0)
						<div> 
							{{ $items[0]['day'] }} ( {{$items[0]['days_left']}} days left ) 
						</div>
								@else 
								<div> 
									{{ $items[0]['day'] }} ( {{abs($items[0]['days_left'])}} days ago ) 
								</div>
								@endif
								@foreach ($items as $item)
								@if ($item['type'] == 'movie')
								<div class="font-bold bg-red-200 mb-1">
									{{$item['name']}} <div class="font-normal">( {{$item['type']}} )</div> 
									</div> 
									@else
									<div class="bg-pink-400 mb-1">
										<div class="font-bold">
											{{$item['show_name']}} 
											<div class="font-normal">( {{$item['type']}} )</div>
										</div>
										<div>{{$item['name']}} 
											@if (!empty($item['ep_number'])) 
											/ S{{$item['ep_season_number']}}E{{$item['ep_number']}} 
											@endif
										</div>
									</div>
								@endif
							@endforeach
							<br>
						</div>
					@endforeach
				@endif
			</div>
		</div>
	</section>
@endif

@endsection