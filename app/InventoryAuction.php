<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryAuction extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['inventory_id', 'ticket_id', 'inventory_auction_number', 'price'];

}
