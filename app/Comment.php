<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['contact_id', 'comment'];
    public $timestamps = false;

    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }
}
