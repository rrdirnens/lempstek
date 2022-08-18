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

        <div class="flex overflow-x-auto">
            @foreach ($dates as $date => $items)
                <div class="min-w-[20%] w-1/5 m-2 p-2 bg-blue-200">
                    <div class="mb-2">{{$date}}</div>
                    @foreach ($items as $item)
                        @if ($item['type'] == 'movie')
                            <div class="font-bold">
                                {{$item['name']}} <div class="font-normal">( {{$item['type']}} )</div> 
                            </div> 
                        @else
                            <div class="font-bold">
                                {{$item['show_name']}} 
                                <div class="font-normal">( {{$item['type']}} )</div>
                            </div>
                            <div>{{$item['name']}} 
                                @if (!empty($item['ep_number'])) 
                                    / S{{$item['ep_season_number']}}E{{$item['ep_number']}} 
                                @endif
                            </div>
                            
                        @endif
                    @endforeach
                    <br>
                </div>
            @endforeach    
            @foreach ($dates as $date => $items)
                <div class="min-w-[20%] w-1/5 m-2 p-2 bg-blue-200">
                    <div class="mb-2">{{$date}}</div>
                    @foreach ($items as $item)
                        @if ($item['type'] == 'movie')
                            <div class="font-bold">
                                {{$item['name']}} <div class="font-normal">( {{$item['type']}} )</div> 
                            </div> 
                        @else
                            <div class="font-bold">
                                {{$item['show_name']}} 
                                <div class="font-normal">( {{$item['type']}} )</div>
                            </div>
                            <div>{{$item['name']}} 
                                @if (!empty($item['ep_number'])) 
                                    / S{{$item['ep_season_number']}}E{{$item['ep_number']}} 
                                @endif
                            </div>
                            
                        @endif
                    @endforeach
                    <br>
                </div>
            @endforeach    
        </div>


        @foreach ($dates as $date => $items)
            <br>
            <div>{{$date}}</div>
            @foreach ($items as $item)
                @if ($item['type'] == 'movie')
                
                Name: {{$item['name']}} ( {{$item['type']}} ) 
                    
                @else
                
                Name: {{$item['show_name']}} ( {{$item['type']}} )
                Episode name: {{$item['name']}}
                @if (!empty($item['ep_number'])) 
                    s{{$item['ep_season_number']}}e{{$item['ep_number']}} 
                @endif
                    
                @endif
            @endforeach
            <br>
        @endforeach

    </div>
</section>
@endsection
