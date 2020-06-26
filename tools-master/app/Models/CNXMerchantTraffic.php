<?php
namespace App\Models;

class CNXMerchantTraffic extends CNXModel
{

    protected $fillable = array('merchant_id',
        'adv_code',
        'campaign_code',
        'ron_redirects_max',
        'min_rpc_ron',
        'min_rpc_merchant',
        'url_type',
        'canonical_domain_override');

    protected $table = 'cnx_merchants_traffic';

    protected $primaryKey = 'merchant_id';
}