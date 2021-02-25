<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscribers extends Model
{
    use HasFactory;

    protected $table = 'subscribers';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'email',
    ];


    protected $casts = [
        
    ];

    protected $hidden = [
    ];

}
