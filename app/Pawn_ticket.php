<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pawn_ticket extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['pawn_id', 'ticket_id'];

    // public function tickets(){
    //     return $this->belongsTo('App\Ticket', 'ticket_id')
    //     ->selectRaw('ticket_id, SUM(net) as balance')
    // }

    public function payment(){
        // dd($payment);
        return $this->hasOne('App\Payment')
        ->selectRaw('pawn_ticket_id,SUM(amount) as amount')
        ->groupBy('pawn_ticket_id');
    }

    public function ticket_child(){
        return $this->belongsTo('App\Ticket', 'ticket_id');
    }

    public function ticket_net(){
        return $this->belongsTo('App\Ticket', 'ticket_id');
        // ->selectRaw('id,SUM(net) as net')
        // ->where('transaction_type', '=', 'renew');
        // ->groupBy(['transaction_type']);
    }

    public function payment_all(){
        // dd($payment);
        return $this->hasMany('App\Payment');
    }

    public function pawn_ticket_payment(){
        // dd($payment);
        return $this->hasOne('App\Payment');
    }

}
