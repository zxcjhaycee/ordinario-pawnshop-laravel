<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['transaction_type', 'inventory_number', 'customer_id', 'branch_id', 'item_category_id', 'is_special_rate','ticket_number','net','transaction_date', 'maturity_date', 'expiration_date', 'auction_date', 'processed_by', 'principal', 'appraised_value', 'status'];

    public function customer(){
        return $this->belongsTo('App\Customer');
    }

    public function pawnTickets(){
        return $this->hasOne('App\Ticket');
    }

    public function inventoryItems(){
        return $this->hasMany('App\Inventory_item');

    }

    public function branch(){
        return $this->belongsTo('App\Branch');
    }

    public function item_category(){
        return $this->belongsTo('App\Item_category');
    }

    public function item(){
        return $this->hasMany('App\Inventory_item');
    }

}
