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
	
	{{-- @push('scripts') --}}
		<script src="js/app.js"></script>	
	{{-- @endpush --}}
</body>
</html>
