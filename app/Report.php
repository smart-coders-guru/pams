<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'report';
    public $timestamps = false;

    function Apps(){
    	return $this->belongsTo(Apps::class);
    }
}
