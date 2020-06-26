<?php
namespace App\Models;

class CNXMerchantDefaults extends CNXModel
{

    protected $fillable = array('adv_code',
        'campaign_code',
        'ron_redirects_max',
        'min_rpc_ron',
        'min_rpc_merchant',
        'url_type',
        'canonical_domain_override');

    protected $table = 'cnx_merchants_defaults';

}