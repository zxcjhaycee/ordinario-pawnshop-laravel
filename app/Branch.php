<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    //
    use SoftDeletes;
    
    protected $fillable = ['branch'];

    public function user(){
        return $this->hasOne('App\User');
    }
}
