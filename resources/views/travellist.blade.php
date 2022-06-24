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
			<li>
				{{env('RAND_VAR')}} <-- env variable test
			</li>
		</ul>
	</div>


	<div>Search for stuff:</div>
	<form action="/" method="POST">
		@csrf
		<input type="text" name="search_query" placeholder="Enter keywords" required>
		<button type="submit">Search</button>
	</form>

	@if (isset($search_results))
		<div style="display:flex;">
			@if ($search_results->tv)
				<div>
					<h1>TV shows</h1>
					@foreach ($search_results->tv as $item)
					<div>
						<strong>{{$item->name}}</strong>
					</div>
					<div class="" style="margin-bottom: 5px;">{{$item->id}}</div>
					@endforeach
				</div>
			@endif
			@if ($search_results->movies)
				<div>
					<h1>Movies</h1>
					@foreach ($search_results->movies as $item)
					<div>
						<strong>{{$item->title}}</strong>
					</div>
					<div class="" style="margin-bottom: 5px;">{{$item->id}}</div>
					@endforeach
				</div>
			@endif
		</div>
	@endif
	
	<div>{{ isset($search_msg) ? $search_msg : '' }}</div>
	
	<script src="js/app.js"></script>	
</body>
</html>
