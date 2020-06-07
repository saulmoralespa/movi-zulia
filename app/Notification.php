<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'token', 'device_id', 'user_id'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
