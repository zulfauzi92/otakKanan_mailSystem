<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $table = 'campaign';
    protected $fillable = [
        'subject',
        'message',
        'user_id'
    ];
    public $timestamps = true;
}
