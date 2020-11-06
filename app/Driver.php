<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'avatar', 'name', 'email',  'plate_number', 'user_id'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
