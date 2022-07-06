<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ShowUser;
use App\Models\MovieUser;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        User::create([
            'name' => config('database.ec_admin.username'),
            'email' => config('database.ec_admin.email'),
            'password' => config('database.ec_admin.password'), // password
            'remember_token' => Str::random(10),
            'admin' => true
        ]);
        
        User::factory(3)->create();
        
        ShowUser::create([
            'user_id' => 1,
            'show_id' => 456
        ]);

        MovieUser::create([
            'user_id' => 1,
            'movie_id' => 234
        ]);
    }
}
