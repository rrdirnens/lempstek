@extends('layout')
@push('head')

@endpush

@section('content')

    <section class="container mx-auto p-4">

        <h1 class="text-xl">Hi, {{$user->name}} </h1>
        
        <br>

        <div class="text-lg mb-6">Not much to see here. All the useful stuff is on the <a class="font-bold hover:text-red-200" href="{{route('home')}}">home page.</a></div>

        <div>Change how many days in the past are displayed on your schedule</div>
        <form action="{{ route('users.edit', $user->id) }}" method="POST">
            @csrf
            <input type="number" name="day_limit" value="{{$user->day_limit}}">
            <button type="submit" class="bg-blue-200 text-lg hover:bg-blue-400 text-blue-900 p-2 rounded-lg">Edit profile</button>
        </form>
        
        @if(session()->has('message'))
            <h1 class="text-green-400">{{session('message')}}</h1>
        @endif
    
        @error('user')
            <p class="text-red-500 text-xs mt-1">{{$message}}</p>
        @enderror
        
    </section>

@endsection
