<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    //
    use SoftDeletes;
    
    protected $fillable = ['branch','address','tin','contact_number'];

    public function user(){
        return $this->hasOne('App\User');
    }

    public function rates(){
        return $this->hasMany(Rate::class);
    }
}
