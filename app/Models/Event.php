<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // allowed
    protected $fillable = [
        'user_id',
        'image',
        'title',
        'description',
        'date',
    ];
}
