<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class events_routes extends Model
{
    use HasFactory;
    protected $table = 'events_routes';
    protected $fillable = [
        'id_user',
        'data',
        'kms',
        'img',
        'participants',
        'maps'
    ];
}
