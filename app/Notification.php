<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'token', 'device_id', 'is_user'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
