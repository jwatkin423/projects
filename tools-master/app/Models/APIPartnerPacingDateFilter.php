<?php
namespace App\Models;

class APIPartnerPacingDateFilter extends PacingDateFilter {

    protected $fillable = ['api_partner','api_name', 'api_id_external', 'first_date', 'second_date', 'third_date'];

    public function api_partners() {
        $result = [];

        $apiPartners = ApiPartner::where('status', '!=', 'inactive')->get();

        foreach($apiPartners as $apiPartner) {
            $list_item_name = "{$apiPartner->api_name} ({$apiPartner->api_id_external})";
            $result[$apiPartner->api_id_external] =  $list_item_name;
        }

        return $result;
    }

    public function getApiPartnerAttribute($attr) {
        $api_id_external = $attr ?? 0;

        return ApiPartner::where('api_id_external', $api_id_external)->first();
    }

    public function getApiNameAttribute() {
        return $this->api_name = $this->api_partner->api_name;
    }

    public function __toString() {
        return sprintf("%s : [%s, %s, %s]", $this->api_name, $this->first_date, $this->second_date, $this->third_date);
    }

}