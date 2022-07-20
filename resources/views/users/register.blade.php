<html>
<head>
	<title>register user</title>

	{{-- tailwindcss cdn script tag --}}
	<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="w-1/2 p-4">
	<h1 class="">Register user</h1>

    <form method="POST" action="/users">
        @csrf
        <div class="mb-6">
          <label for="name" class="inline-block text-lg mb-2"> Name </label>
          <input type="text" class="border border-gray-200 rounded p-2 w-full" name="name" value="{{old('name')}}" />
  
          @error('name')
          <p class="text-red-500 text-xs mt-1">{{$message}}</p>
          @enderror
        </div>
  
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
          <label for="password2" class="inline-block text-lg mb-2">
            Confirm Password
          </label>
          <input type="password" class="border border-gray-200 rounded p-2 w-full" name="password_confirmation"
            value="{{old('password_confirmation')}}" />
  
          @error('password_confirmation')
          <p class="text-red-500 text-xs mt-1">{{$message}}</p>
          @enderror
        </div>
  
        <div class="mb-6">
          <button type="submit" class="bg-black text-white rounded py-2 px-4 hover:bg-blue-600 ">
            Sign Up
          </button>
        </div>

        <div class="mt-8">
          <p>
            Already have an account?
            <a href="/login" class="text-laravel">Login</a>
          </p>
        </div>
      </form>

	
	<script src="js/app.js"></script>	
</body>
</html>
