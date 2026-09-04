<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    //
        use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'body'
    ];
    // user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // self create
    public static function logAction($user_id,$action,$body)
    {
       $log = self::create([
            'user_id'=>$user_id,
            'action'=>$action,
            'body'=>$body
        ]);
    return $log;
    }
}
