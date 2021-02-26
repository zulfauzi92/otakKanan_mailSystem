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
        'track_click',
        'track_open'
    ];
    public $timestamps = true;
}
