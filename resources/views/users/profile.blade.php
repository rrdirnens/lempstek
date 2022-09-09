@extends('layout')
@push('head')

@endpush

@section('content')

    <section class="container mx-auto p-4">

        <h1 class="text-xl mb-6">Hi, {{$user->name}} </h1>
                
        <h2 class="text-lg mb-4">Settings:</h2>
        <div class="flex flex-col">
            <form action="{{ route('users.edit', $user->id) }}" method="POST">
                @csrf
                <div class="form-group mb-4 flex flex-row items-center justify">
                    <label for="day_limit" class="mr-2">Day limit</label>
                    <input type="number" name="day_limit" value="{{$user->day_limit}}" class="border border-stone-900 py-1 px-2 w-20 mr-2">
                    <p class="text-sm text-stone-400">Changes how many days in the past are displayed on your schedule (only relevant for movies)</p>
                </div>
                <button type="submit" class="bg-rose-200 text-lg hover:bg-rose-300 mt-6 py-2 px-4 border border-2 border-stone-900">Save changes</button>
            </form>
        </div>
        
        @if(session()->has('message'))
            <h1 class="text-green-400">{{session('message')}}</h1>
        @endif
    
        @error('user')
            <p class="text-red-500 text-xs mt-1">{{$message}}</p>
        @enderror
        
    </section>

@endsection
