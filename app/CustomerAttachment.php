<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAttachment extends Model
{
    //
    use SoftDeletes;
    protected $table = 'customers_attachments';

    protected $fillable = ['customer_id', 'attachment_id', 'number'];

    public function customers_attachments(){
        return $this->belongsTo('App\Customer', 'id', 'customer_id');
    }
}
