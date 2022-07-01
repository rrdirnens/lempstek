<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieUser extends Model
{
    use HasFactory;

    protected $table = 'movie_user'; // enforce use of singular table name. Otherwise, Laravel will pluralize the table name.

    protected $fillable = [
        'user_id',
        'movie_id',
    ];
}
