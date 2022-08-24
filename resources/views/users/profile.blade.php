@extends('layout')
@push('head')

@endpush

@section('content')

    <section class="container mx-auto p-4">

        <h1 class="text-xl">Hi, {{$user->name}} </h1>
        
        <br>

        <div class="text-lg">Not much to see here. All the useful stuff is on the <a class="font-bold hover:text-red-200" href="{{route('home')}}">home page.</a></div>

        @if(session()->has('message'))
            <h1 class="text-green-400">{{session('message')}}</h1>
        @endif
    
        @error('user')
            <p class="text-red-500 text-xs mt-1">{{$message}}</p>
        @enderror
        
    </section>

@endsection
