<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as BasicAuthenticatable;

class Apps extends Model implements Authenticatable
{
      protected $table = 'apps';
      public $timestamps = false;
      use BasicAuthenticatable;
      protected $fillable = ['login', 'password'];
      
    function Reports(){
    	return $this->hasMany(Report::class);	
    }  

    public function getAuthLogin()
    {
        return $this->apps_login;
    }

    public function getAuthPassword()
    {
        return $this->apps_password;
    }
}
