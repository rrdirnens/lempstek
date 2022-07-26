<html>
<head>
	<title>Entertainment calendar v0.1</title>

	{{-- tailwindcss cdn script tag --}}
	<script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <nav class="bg-blue-200 p-2">
        <div class="container mx-auto">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold">Entertainment calendar</a>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl p-2 font-bold">Home</a>
                    
                    @auth
                    
                    <a href="{{ route('users.profile', 7) }}" class="text-xl p-2 font-bold">Profile</a>
                        <form action="/logout" method="POST" class="mb-0">
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
        
	@yield('content')
	
	<script src="js/app.js"></script>	
</body>
</html>
