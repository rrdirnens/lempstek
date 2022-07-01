<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowUser extends Model
{
    use HasFactory;

    protected $table = 'show_user'; // enforce use of singular table name. Otherwise, Laravel will pluralize the table name.
    
    protected $fillable = [
        'user_id',
        'show_id',
    ];
}
