<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Customer extends Model
{
    //
    use SoftDeletes;
    protected $table = 'customers';
    protected $fillable = ['first_name', 'middle_name', 'last_name', 'suffix', 'sex', 'birthdate', 'civil_status', 'email', 'contact_number', 'alternate_number',
    'present_address', 'present_address_two', 'present_area', 'present_city', 'present_zip_code', 'permanent_address', 'permanent_address_two', 'permanent_area',
    'permanent_city', 'permanent_zip_code'];

    public function attachments(){
        return $this->belongsToMany('App\Attachment', 'customers_attachments' , 'customer_id', 'attachment_id')->withPivot('id','number', 'path', 'deleted_at')->wherePivot('deleted_at', null);
        // return $this->belongsToMany('App\Attachment');
    }

    

}
