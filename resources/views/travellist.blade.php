<html>
<head>
	<title>Entertainment calendar v0.1</title>
</head>


<body>
	<h1>Entertainment calendar v0.1 || {{config('app.env')}}</h1>

	<div>
		<ul>
			<li>
				{{config('app.env')}}
			</li>
			<li>
				{{config('app.debug')}}
			</li>
		</ul>
	</div>
	@php
		// dd($entertainment)
	@endphp

	@foreach ($entertainment as $item)
		
		<h2>{{$item['name']}}</h2>
		<div><strong>Last ep: </strong> {{$item['last_episode_to_air']->air_date ?? 'no info'}}</div>
		<div><strong>Next ep: </strong> {{$item['next_episode_to_air']->air_date ?? 'no info'}}</div>

	@endforeach
	
	{{-- @push('scripts') --}}
		<script src="js/app.js"></script>	
	{{-- @endpush --}}
</body>
</html>
