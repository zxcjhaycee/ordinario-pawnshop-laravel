<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    //
    protected $fillable = ['control_id', 'inventory_auction_number', 'price', 'auction_date'];
}
