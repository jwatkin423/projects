<?php
namespace App\Models;

use DB;

class CommerceAccounts extends BaseModel {

    protected $table = 'commerce_accounts';

    protected $primaryKey = 'account_id';

    protected $fillable = [
        'adv_key',
        'campaign_code',
        'country_code',
        'currency_code',
        'account_name',
        'app_class',
        'publisher_id',
        'login',
        'pwd',
        'pwd_alt',
        'api_key',
        'merchant_url_param',
        'ftp_host',
        'account_type'
    ];

    public function getAdvKeys($query = null) {
        $all = [];

        $adv_codes_raw = $this->select(DB::raw('commerce_accounts.adv_code'))
                    ->addSelect('campaign_code')
                    ->join('advertisers', 'commerce_accounts.adv_code', '=', 'advertisers.adv_code')
                    ->where('adv_type', '=', 'commerce')
                    ->where('status', '=', 'active');

        if ($query) {
            $adv_codes_raw = $adv_codes_raw->where('commerce_accounts.adv_code', 'LIKE', "%{$query}%");
        }

        $adv_codes_raw = $adv_codes_raw->orderBy('adv_code')
                    ->get()
                    ->toArray();

        $rows = $this->convertAdvKeyToList($adv_codes_raw);

        $adv_keys = array_merge($all, $rows);

        return $adv_keys;
    }

    public function getAdvCodes() {

        $adv_codes = $this->select(DB::raw('DISTINCT(adv_code) as adv_code'))
                    ->orderBy('adv_code')
                    ->get()
                    ->pluck('adv_code', 'adv_code')->toArray();

        return $adv_codes;

    }

    public function getCampaignCodes() {
        return $this->select(DB::raw('DISTINCT(campaign_code) as campaign_code'))
                    ->orderBy('campaign_code')
                    ->get()
                    ->pluck('campaign_code', 'campaign_code');
    }


    private function convertAdvKeyToList($adv_keys) {

        $list = [];

        foreach ($adv_keys as $code) {
            $adv_code = $code['adv_code'];
            $campaign_code = $code['campaign_code'];
            $adv_key = "{$adv_code}_{$campaign_code}";

            $list[$adv_key] = $adv_code . "_" . $campaign_code;
        }

        return $list;

    }

}