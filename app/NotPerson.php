<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotPerson extends Model
{
    protected $fillable = ['contact_id', 'type', 'title'];
    public $timestamps = false;
}
