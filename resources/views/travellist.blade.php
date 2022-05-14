<html>
<head>
	<title>Travel List</title>
</head>

<body>
	<h1>My Travel Bucket ist</h1>
	<h2>Places I'd Like to Visit</h2>
	<ul>
	  @foreach ($togo as $newplace)
		<li>{{ $newplace->name }}</li>
	  @endforeach
	</ul>

	<h2>Places I've Already Been To</h2>
	<ul>
          @foreach ($visited as $place)
                <li>{{ $place->name }}</li>
          @endforeach
	</ul>
	@php
		function function1(): bool
		{
			$use = 1;
			do {
				echo "The number is: $use <br>";
				$use++;
			} while ($use <= 5);
			for ($use = 0; $use <= 100; $use += 10) {
				echo "The number is: $use <br>";
			}
			return false;
		}


		function function2(): bool
		{
			$use = 1;
			do {
				echo "The number is: $use <br>";
				$use++;
			} while ($use <= 5);
			for ($use = 0; $use <= 100; $use += 10) {
				echo "The number is: $use <br>";
			}
			return false;
		}

	@endphp
</body>
</html>
