<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory_item extends Model
{
    //
    use SoftDeletes;
    protected $table = 'inventory_items';
    protected $fillable = ['inventory_id','item_type_id', 'item_name', 'interest', 'item_type_appraised_value', 'item_name_appraised_value', 'description', 'image', 'item_karat', 'item_type_weight', 'item_name_weight', 'item_karat_weight', 'status'];


    public function item_type(){
        return $this->belongsTo('App\Item_type');
    }
}
