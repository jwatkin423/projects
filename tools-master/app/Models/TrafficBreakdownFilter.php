<?php
namespace App\Models;

use App\Models\Campaign;
use PhpParser\Builder;

class TrafficBreakdownFilter extends BaseModel {

    public $fillable = ['advertiser', 'partner', 'traffic'];


    public static function activeCampaigns() {
        return self::makeHash(Campaign::where('status','active')->get(), 'id', 'id', true);
    }

    public static function partners() {
        $options = ApiPartner::getAllAsHash();
        $options = array('' => '- All -') + $options;
        return $options;
    }



    protected static function makeHash($objects, $key_field, $value_field, $with_empty = false) {
        $result = [];

        if ( $with_empty ) {
            $result[''] = '- All -';
        }

        foreach($objects as $object) {
            $result[$object->$key_field] = $object->$value_field;
        }

        return $result;
    }

    public function getAdvCodeAttribute()
    {
        return substr($this->advertiser,0,strpos($this->advertiser,'_'));
    }
    public function getCampaignCodeAttribute()
    {
        return substr($this->advertiser,strpos($this->advertiser,'_')+1);
    }

}
