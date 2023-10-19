<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',

    ];
    protected $primaryKey = 'id';
    protected $table = 'users_status';
}
