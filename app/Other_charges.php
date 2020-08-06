<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Other_charges extends Model
{
    //
    use SoftDeletes;
    
    protected $fillable = ['charge_type', 'charge_name', 'amount'];


}
