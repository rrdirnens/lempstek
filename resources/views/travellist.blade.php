<html>
<head>
	<title>Entertainment calendar v0.1</title>

	{{-- tailwindcss cdn script tag --}}
	<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="p-4">
	<nav>
		<div class="container mx-auto">
			<div class="flex justify-between items-center">
				<div class="flex items-center">
					<a href="{{ route('home') }}" class="text-2xl font-bold">Entertainment calendar</a>
				</div>
				<div class="flex items-center">
					<a href="{{ route('home') }}" class="text-xl p-2 font-bold">Home</a>
					@auth
					<form action="/logout" method="POST">
						@csrf
						<button type="submit" class="text-xl p-2 font-bold">
							Logout
						</button>
					</form>
					@else
					<a href="{{ route('register') }}" class="text-xl p-2 font-bold">Register</a>
					<a href="{{ route('login') }}" class="text-xl p-2 font-bold">Login</a>
					@endauth
				</div>
			</div>
		</div>
	</nav>

	<h1 class="mb-4 text-primary text-xl">Entertainment calendar v0.1 || {{config('app.env')}}</h1>

	<div class="mb-4">
		<ul>
			<li>
				- {{config('app.env')}}
			</li>
			<li>
				- {{config('app.debug')}}
			</li>
		</ul>
	</div>

	<div style="margin-bottom:30px;">
		<h2>USER ACTIONS</h2>
		<a href="/register" class="inline-block p-4 text-slate-50 bg-slate-600 rounded">Register</a>
		@if(session()->has('message'))
			<h3 class="text-green-400">{{session('message')}}</h3>
		@endif
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
