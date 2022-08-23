@extends('layout')
@push('head')

@endpush

@section('content')

<section class="container mx-auto p-4">

    <h1 class="text-xl">Hi, {{$user->name}} </h1>
    
    <br>

    @if(session()->has('message'))
    	<h1 class="text-green-400">{{session('message')}}</h1>
	@endif
   
    @error('user')
		<p class="text-red-500 text-xs mt-1">{{$message}}</p>
	@enderror
	
    <div class="mb-4">
        <h2 class="mb-4">MOVIES</h2>
        <div class="flex flex-wrap">
            @foreach ($movies as $movie)
                <div class="item-button">
                    <div class="item-button__container">
                        {{$movie->details->title}}
                    </div>
                    <div class="item-button__overlay">
                        <a class="item-button__action item-button__action--view" href="{{route('movies.show', $movie->movie_id)}}">View</a>
                        <div class="item-button__divider">|</div>
                        <a class="item-button__action item-button__action--remove" href="{{route('users.movie.delete', $movie->movie_id)}}">Remove</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-4">
        <h2 class="mb-4">SHOWS</h2>
        <div class="flex flex-wrap">
            @foreach ($shows as $show)
                <div class="item-button">
                    <div class="item-button__container">
                        {{$show->details->name}}
                    </div>
                    <div class="item-button__overlay">
                        <a class="item-button__action item-button__action--view" href="{{route('shows.show', $show->show_id)}}">View</a>
                        <div class="item-button__divider">|</div>
                        <a class="item-button__action item-button__action--remove" href="{{route('users.tv.delete', $show->show_id)}}">Remove</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-4">
        <h2 class="mb-4">YER FOOKING SCHEDULE</h2>        
        <div class="flex overflow-x-auto">
            @foreach ($dates as $date => $items)
                <div class="min-w-[20%] w-1/5 m-2 p-2 bg-blue-200">
                    <div class="mb-2">{{$date}} @if ($items[0]['is_today']) | TODAY @endif</div>
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
        </div>
    </div>
</section>
@endsection
