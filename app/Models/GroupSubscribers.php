<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSubscribers extends Model
{
    use HasFactory;

    protected $table = 'group_subscribers';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'group_id',
        'subscribe_id'
    ];


    protected $casts = [
        
    ];

    protected $hidden = [
    ];

}
