<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['transaction_type', 'ticket_id', 'or_number', 'amount', 'inventory_id'];


}
