<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['contact_id', 'address', 'type'];
    public $timestamps = false;

    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }
}
