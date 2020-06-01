<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['id', 'isPerson', 'photoName'];
    public $timestamps = false;


    public function numbers()
    {
        return $this->hasMany('App\Number');
    }
    public function emails()
    {
        return $this->hasMany('App\Email');
    }
    public function addresses()
    {
        return $this->hasMany('App\Address');
    }
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }

    public function detail()
    {
        if ($this->isPerson)
            return $this->hasOne('App\Person');
        else if (!$this->isPerson)
            return $this->hasOne('App\NotPerson');
        return null;
    }
}
