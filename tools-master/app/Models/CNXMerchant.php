<?php
namespace App\Models;

class CNXMerchant extends BaseModel
{

    public $incrementing = false;
    protected $fillable = array('merchant_id', 'adv_code', 'campaign_code', 'merchant_name', 'merchant_url', 'canonical_domain', 'active');
    protected $table = 'cnx_merchants';

    /* Make composite primary key working */

    public static function find($id, $columns = array('*'))
    {
        $obj = self::fromKey($id);

        return self::where('adv_code', '=', $obj->adv_code)
            ->where('campaign_code', '=', $obj->campaign_code)->first();
    }

    public static function fromKey($id)
    {
        list($merchant_id, $adv_code, $campaign_code) = explode('_', $id);

        $obj = new self();
        $obj->merchant_id = $merchant_id;
        $obj->adv_code = $adv_code;
        $obj->campaign_code = $campaign_code;

        return $obj;
    }

    public function getIdAttribute()
    {
        return $this->adv_code . "_" . $this->campaign_code;
    }

    public function merchantTraffic()
    {

        return $this->hasOne('CNXMerchantTraffic', 'merchant_id', 'merchant_id');
    }

    protected function setKeysForSaveQuery(Builder $query)
    {
        return $query->where('merchant_id', '=', $this->merchant_id)
            ->where('adv_code', '=', $this->adv_code)
            ->where('campaign_code', '=', $this->campaign_code);
    }

    protected function getRonRedirectsMaxAttribute()
    {
        $traffic = $this->merchantTraffic;

        if (!$traffic) {
            return $this->defaults->ron_redirects_max;
        }

        return $traffic->ron_redirects_max;
    }

    protected function getMinRpcRonAttribute()
    {
        $traffic = $this->merchantTraffic;

        if (!$traffic) {
            return $this->defaults->min_rpc_ron;
        }

        return $traffic->min_rpc_ron;
    }

    protected function getMinRpcMerchantAttribute()
    {
        $traffic = $this->merchantTraffic;

        if (!$traffic) {
            return $this->defaults->min_rpc_merchant;
        }

        return $traffic->min_rpc_merchant;
    }

    protected function getUrlTypeAttribute()
    {
        $traffic = $this->merchantTraffic;

        if (!$traffic) {
            return $this->defaults->url_type;
        }

        return $traffic->url_type;
    }

    protected function getDefaultsAttribute()
    {

        return CNXMerchantDefaults::where('adv_code', $this->adv_code)
            ->where('campaign_code', $this->campaign_code)
            ->first();
    }
}