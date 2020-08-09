<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket_item extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['ticket_id', 'inventory_item_id', 'item_type_appraised_value', 'item_name_appraised_value'];

    public function inventory_items(){
        return $this->belongsTo('App\Inventory_item', 'inventory_item_id');
    }
}
