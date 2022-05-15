<html>
<head>
	<title>Travel List</title>
</head>


<body>
	<h1>dev env</h1>
	<h2>Places I'd Like to Visit</h2>
	<ul>
		@foreach ($togo as $newplace)
		<li>{{ $newplace->name }}</li>
		@endforeach
	</ul>
	
	<h2>Places I've Already Beenwevwe To</h2>
	<ul>
		@foreach ($visited as $place)
		<li>{{ $place->name }}</li>
		@endforeach
	</ul>
	{{-- @push('scripts') --}}
		<script src="js/app.js"></script>	
	{{-- @endpush --}}
</body>
</html>
