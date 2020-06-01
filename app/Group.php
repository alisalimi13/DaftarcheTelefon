<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['id', 'title', 'photoName'];
    public $timestamps = false;

    public function contacts()
    {
        return $this->belongsToMany('App\Contact');
    }
}
