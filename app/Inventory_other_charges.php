<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory_other_charges extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['inventory_id', 'ticket_id', 'other_charges_id', 'amount'];

    public function inventory_other_charges(){
        return $this->belongsTo('App\Other_charges', 'other_charges_id');
    }
}
