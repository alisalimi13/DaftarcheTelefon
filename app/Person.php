<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = ['contact_id', 'firstName', 'lastName', 'isMale'];
    public $timestamps = false;
}
