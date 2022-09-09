@extends('layout')
@push('head')

@endpush

@section('content')

    <section class="container mx-auto p-4">

        <h2 class="text-xl font-bold">Why does this exist?</h2>
        
        <div class="mb-10 ml-6">
            <span class="font-bold">1.</span> I made it for myself, to keep track of when new episodes come out for the shows I watch. It was a pain in the ass to check multiple google searches every time I sat down with my hot dinner and needed something new to watch.
            <br>
            <span class="font-bold">2.</span> I wanted to work on a project that solved an actual problem I had. It didn't matter to me whether or not there are similar solutions already available (still don't know, btw :D). I just wanted to practice and learn and the best way to do that was by building something.
        </div>

        <h2 class="text-xl font-bold">Is the source code available?</h2>
        
        <div class="mb-10 ml-6">Yup: 
            <a href="mailto:rob.dir.devmail@gmail.com" class="font-bold">https://github.com/rrdirnens/lempstek</a>
        </div>

        <h2 class="text-xl font-bold">How can I report a bug?</h2>
        
        <div class="mb-10 ml-6">Holla at ya boi: 
            <a href="mailto:rob.dir.devmail@gmail.com" class="font-bold">rob.dir.devmail@gmail.com</a>
        </div>
        
        <h2 class="text-xl font-bold">Can you add a feature?</h2>
        
        <div class="mb-10 ml-6">Holla at ya boi: 
            <a href="mailto:rob.dir.devmail@gmail.com " class="font-bold">rob.dir.devmail@gmail.com</a>
        </div>

        <h2 class="text-xl font-bold">Can I hire you?</h2>
        
        <div class="mb-10 ml-6">Holla at ya boi: 
            <a href="mailto:rob.dir.devmail@gmail.com" class="font-bold">rob.dir.devmail@gmail.com</a>
        </div>

        {{-- <h2 class="text-xl font-bold">Can I contribute?</h2>

        <div class="text-lg mb-10 ml-6">You can fork the project on GitHub and submit a pull request. I'll review it and merge it if it's good. If you want to contribute but don't know how to code, you can also submit a feature request on GitHub.</div> --}}
        
   
    </section>

@endsection
