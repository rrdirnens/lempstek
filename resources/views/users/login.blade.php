
@extends('layout')

@section('content')
    
<section class="container mx-auto pt-4 md:w-1/2 md:px-0 px-4">

    <h1 class="text-xl">Login user</h1>

    <form method="POST" action="/users/authenticate">
        @csrf
        <div class="mb-6">
            <label for="email" class="inline-block text-lg mb-2">Email</label>
            <input type="email" class="border border-gray-200 rounded p-2 w-full" name="email" value="{{old('email')}}" />
            
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{$message}}</p>
            @enderror
        </div>
        
        <div class="mb-6">
            <label for="password" class="inline-block text-lg mb-2">
            Password
            </label>
            <input type="password" class="border border-gray-200 rounded p-2 w-full" name="password"
            value="{{old('password')}}" />
            
            @error('password')
            <p class="text-red-500 text-xs mt-1">{{$message}}</p>
            @enderror
        </div>
        
        <div class="mb-6">
            <button type="submit" class="bg-black text-white rounded py-2 px-4 hover:bg-blue-600 ">
                Login
            </button>
        </div>
    </form>

</section>
@endsection
