<?php
namespace App\Models;

class TrafficDashboardFilter extends BaseModel {

    public $fillable = array('advertiser', 'campaign', 'inventory');

    public static function advertisers() {
        return self::makeHash(Advertiser::all(), 'id', 'id', true);
    }

    public static function campaigns() {
        return self::makeHash(Campaign::all(), 'id', 'id', true);
    }

    public function getCampaignCodeAttribute() {
        if(strpos($this->campaign, '_') !== false) {
            $c = Campaign::fromKey($this->campaign);
            return $c->campaign_code;
        }
    }

    public static function inventories() {
        $options = ApiPartner::getAllAsHash();
        $options = array('' => '- All -') + $options;
        return $options;
    }

    protected static function makeHash($objects, $key_field, $value_field, $with_empty = false) {
        $result = array();

        if ( $with_empty ) {
            $result[''] = '- All -';
        }

        foreach($objects as $object) {
            $result[$object->$key_field] = $object->$value_field;
        }

        return $result;
    }

}
