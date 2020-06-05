<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = ['branch_id', 'item_type_id', 'karat', 'gram', 'regular_rate', 'special_rate', 'description'];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
    
    public function item_types(){
        return $this->belongsTo(Item_type::class);
    }

    public function getItem($branch_id, $item_type_id)
    {
        return $this->where([
            ['branch_id', '=', $branch_id],
            ['item_type_id', '=', $item_type_id]
        ])->orderBy('karat', 'DESC')->get();
    }
}
