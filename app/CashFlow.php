<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    //
    protected $guarded = [];

    public function cashFlowsDetails(){
        return $this->hasMany('App\CashFlowsDetail');
    }
}
