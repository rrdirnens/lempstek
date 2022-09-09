
@extends('layout')

@section('content')
    
<section class="container mx-auto pt-4 md:w-1/2 md:px-0 px-4">

    <h1 class="text-xl mb-12 font-bold">Login</h1>

    <form method="POST" action="/users/authenticate">
        @csrf
        <div class="mb-6">
            <label for="email" class="inline-block text-lg mb-2">Email</label>
            <input type="email" class="border border-stone-900 border-2 p-2 w-full" name="email" value="{{old('email')}}" />
            
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{$message}}</p>
            @enderror
        </div>
        
        <div class="mb-6">
            <label for="password" class="inline-block text-lg mb-2">
            Password
            </label>
            <input type="password" class="border border-stone-900 border-2 p-2 w-full" name="password"
            value="{{old('password')}}" />
            
            @error('password')
            <p class="text-red-500 text-xs mt-1">{{$message}}</p>
            @enderror
        </div>
        
        <div class="mb-6">
            <button type="submit" class="py-2 px-4 hover:bg-rose-300 bg-rose-200">
                Login
            </button>
        </div>

        <div class="mt-10">
            <p>
                Don't have an account?
                <a href="{{ route('register') }}" class="font-bold">Register</a>
            </p>
        </div>
    </form>

</section>
@endsection
