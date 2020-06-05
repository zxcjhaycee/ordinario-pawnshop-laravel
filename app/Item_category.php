<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item_category extends Model
{
    protected $fillable = ['item_category'];

    // public function rates(){
    //     return $this->hasMany(Rate::class);
    // }

    public function item_types(){
        return $this->hasMany(Item_type::class);
    }
}
