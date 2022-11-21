<html>

<head>
    <title>Entertainment calendar v0.1</title>

    {{-- viewport --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('head')
</head>

<body>
    <nav class="py-2 px-4">
        <div class="container mx-auto">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" title="Home" class="text-2xl font-bold">Entertainment calendar</a>
                </div>
                <div class="header-menu">
                    <a href="{{ route('home') }}" title="Home" class="header-menu__item--regular ">
                        <img class="w-8 h-8" src="../images/icons/solid/home.svg" alt="home">
                    </a>
                    <a href="{{ route('about') }}" title="About" class="header-menu__item--regular ">
                        <img class="w-8 h-8" src="../images/icons/solid/question.svg" alt="About">
                    </a>


                    <div class="profile-menu">
                        <img class="profile-btn cursor-pointer" src="../images/icons/solid/profile.svg" alt="profile">
                        <div class="profile-drawer">
                            @auth
                            <a href="{{ route('users.profile', auth()->user()->id) }}" class="profile-drawer-item">
                                Profile
                            </a>
                            <form action="{{ route('users.logout') }}" method="POST" class="mb-0">
                                @csrf
                                <button type="submit" title="Logout" class="profile-drawer-item">
                                    Logout
                                </button>
                            </form>
                            @else
                            <a href="{{ route('register') }}" class="profile-drawer-item">Register</a>
                            <a href="{{ route('login') }}" class="profile-drawer-item">
                                Login
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto errors-and-messages p-4 w-full">
        <div class="border-dashed  border-2 border-stone-900 p-2">
            <p class="text-stone-900 text-xl">session('message')</p>
            <p class="text-red-500 text-xl mt-1">$message (for errors)</p>
        </div>

        @if (session()->has('message'))
        <h1 class="text-green-700 text-xl">{{ session('message') }}</h1>
        @endif

        @error('user_show')
        <p class="text-red-500 text-xl mt-1">{{ $message }}</p>
        @enderror

        @error('user_movie')
        <p class="text-red-500 text-xl mt-1">{{ $message }}</p>
        @enderror

        @error('user')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    @yield('content')

    <script src="../js/app.js"></script>
</body>

</html>
