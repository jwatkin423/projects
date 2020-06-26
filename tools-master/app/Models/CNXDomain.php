<?php
namespace App\Models;

class CNXDomain extends CNXModel {


    protected $fillable = array('domain', 'merchant_id', 'adv_code', 'campaign_code');
    protected $table = 'cnx_domains';
    protected $primaryKey = 'domain';

    public static $rules = array(
        'domain'        => 'required',
        'merchant_id'   => 'required',
        'adv_code'      => 'required',
        'campaign_code' => 'required',
    );


}
