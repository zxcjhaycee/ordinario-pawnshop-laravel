<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
    //
    use SoftDeletes;
    // protected $fillable = ['inventory_id', 'ticket_id', 'notice_yr', 'notice_ctrl', 'notice_date'];
    protected $guarded = [];
    public function ticket(){
        return $this->belongsTo('App\Ticket');
    }
}
