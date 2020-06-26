<?php

namespace App\Models;

class Merchants extends BaseModel {

    protected $table = "commerce_merchants_all";

    protected $primaryKey = 'merchant_id';

    public function getMerchants($query, $adv_key, $convertToKeyPair = 1) {
        $merchants = $this->select(['merchant_id', 'merchant_name', 'adv_code', 'campaign_code'])
                          ->where('merchant_name', 'LIKE', '%' . $query . '%');
        if($adv_key != 'all') {

            if (list($adv_code, $campaign_code) = explode("_", $adv_key)) {
                $merchants = $merchants->where('adv_code', '=', $adv_code)
                                       ->where('campaign_code', '=', $campaign_code);
            }

        }

        $merchants = $merchants->get();

        if ($convertToKeyPair) {
            return $this->convertToKeyPair($merchants);
        }

        return $merchants;

    }

    public function getMerchantsByInt($query, $adv_key) {

        $merchants = $this->select(['merchant_id', 'merchant_name', 'adv_code', 'campaign_code'])
                          ->where('merchant_id', 'LIKE', '%' . $query . '%');

        if ($adv_key != 'all') {

            if (list($adv_code, $campaign_code) = explode("_", $adv_key)) {
                $merchants = $merchants->where('adv_code', '=', $adv_code)
                                       ->where('campaign_code', '=', $campaign_code);
            }
        }

        $merchants = $merchants->limit(10)->get();

        return $this->convertToKeyPair($merchants, 1);

    }

    private function convertToKeyPair($merchants, $type = 0) {

        $temp = [];

        foreach ($merchants as $row) {
            $id = $row->merchant_id;
            $name = $row->merchant_name;
            $adv_code = $row->adv_code;
            $campaign_code = $row->campaign_code;
            $adv_key = "{$adv_code}_{$campaign_code}";

            if (!$type) {
                $temp[] = ['id' => $id, 'merchant_name' => $name . " ({$id}) ($adv_key)", 'adv_key' => $adv_key];
            } else {
                $temp[] = ['id' => $id, 'merchant_id' => $id . " ({$name}) ($adv_key)", 'adv_key' => $adv_key, 'merchant_name' => $name];
            }
        }

        return $temp;

    }

}
