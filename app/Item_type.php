<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item_type extends Model
{
    protected $fillable = ['item_category_id', 'item_type'];

    public function item_category(){
        return $this->belongsTo(Item_category::class);
    }

    public function items(){
        return $this->hasMany(Item::class);
    }
}
