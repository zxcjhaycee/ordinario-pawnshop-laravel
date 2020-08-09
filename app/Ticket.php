<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['inventory_id', 'ticket_number', 'transaction_type', 'transaction_status', 'transaction_date', 'maturity_date', 'expiration_date', 'auction_date', 'attachment_id', 'processed_by', 'net', 'attachment_number','discount', 'discount_remarks', 'authorized_representative', 'interbranch', 'interbranch_renewal', 'interest', 'penalty', 'advance_interest', 'interest_text', 'penalty_text', 'charges', 'is_special_rate', 'appraised_value', 'principal', 'status'];

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
    
    public function pawn_tickets(){
        return $this->hasOne('App\Pawn_ticket');
    }

    public function pawn_parent(){
        return $this->hasOne('App\Pawn_ticket', 'pawn_id');
    }
    public function pawn_parent_many(){
        return $this->hasMany('App\Pawn_ticket', 'pawn_id');
    }

    public function item_tickets(){
        return $this->hasMany('App\Ticket_item');
    }
}
