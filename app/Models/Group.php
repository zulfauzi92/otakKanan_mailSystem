<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'group';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'name',
    ];


    protected $casts = [
        
    ];

    protected $hidden = [
    ];

}
