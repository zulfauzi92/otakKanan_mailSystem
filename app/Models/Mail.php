<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    use HasFactory;
    protected $table = 'mail';

    public $timestamps = true;

    protected $fillable = [
        'from_id',
        'to_id',
        'campaign_id',
        'track_click',
        'track_open'
    ];


    protected $casts = [
        
    ];

    protected $hidden = [
    ];
}
