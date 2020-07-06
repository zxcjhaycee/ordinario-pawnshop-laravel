<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['inventory_id', 'ticket_number', 'transaction_type', 'transaction_status', 'transaction_date', 'maturity_date', 'expiration_date', 'auction_date', 'attachment_id', 'processed_by', 'net', 'attachment_number','discount', 'discount_remarks', 'authorized_representative', 'interbranch', 'interbranch_renewal'];

    public function other_charges(){
        return $this->hasMany('App\Inventory_other_charges');
    }

    public function encoder(){
        return $this->belongsTo('App\User', 'processed_by');
    }

    public function attachment(){
        return $this->belongsTo('App\Attachment');
    }

    public function inventory(){
        return $this->belongsTo('App\Inventory');
    }
}
