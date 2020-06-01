<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    protected $fillable = ['contact_id', 'number', 'type'];
    public $timestamps = false;

    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }
}
