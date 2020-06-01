<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = ['contact_id', 'email'];
    public $timestamps = false;

    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }
}
