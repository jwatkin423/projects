<?php
namespace App\Models;

class ApiPartner extends BaseModel {

    public $table = 'api_partners';

    public static $rules = [
        'api_id_external' => 'required|integer',
        'api_name' => 'required|max:255',
        'api_key' => 'required|max:255',
        'filter_type' => 'required|in:all,ua-cc,none',
        'country_code' => 'required|max:4',
        'status' => 'required|in:inactive,prod,dev',
        'api_partner_email' => 'required',
    ];
    protected $fillable = [
        'api_id',
        'api_id_external',
        'api_name',
        'api_key',
        'filter_type',
        'country_code',
        'api_partner_email',
        'cost_multiplier',
        'portfolio',
        'api_key_string',
        'status',
        'domain_report'
    ];
    protected $primaryKey = 'api_id';

    public static function randomApiIdExternal() {
        return rand(100, 1000);
    }

    public function getMaxID() {
        $maxID = DB::table('api_partners')->max('api_id');
        $maxID += 1;
        return $maxID;
    }

    public static function getAllAsHash() {

        $result = self::withoutInactive()->get();
        $hash = [];

        foreach ($result as $partner) {
            $hash[$partner->api_id_external] = $partner->api_id_external . " - " . $partner->api_name;
        }

        return $hash;
    }

    public static function scopeWithoutInactive($query) {
        return $query->where('status', '!=', 'inactive');
    }

    public static function countryCode() {
        return [
            "All" => "ALL",
            "US" => "US",
            "INTL" => "INTL"
        ];
    }

    public static function status() {
        return array(
            "prod" => "prod",
            "dev" => "dev",
            "inactive" => "inactive"
        );
    }

    public static function filterType() {
        return [
            "all" => "all",
            "ua-cc" => "ua-cc",
            "none" => "none"
        ];
    }

    public function getIdAttribute() {
        return $this->api_id;
    }

    public function scopeMaxID() {
        $maxID = DB::table('api_partners')->max('api_id');
        $maxID += 1;
        return $maxID;
    }

    public function scopeApiID() {
        return $this->where('portfolio', '=', 'internal')->pluck('api_id_external', 'api_id_external');
    }

    public function fromKey($api_partner) {
        $api_id_external = $api_partner->api_id_external;
        $ApiPartner = new ApiPartner();

        return $ApiPartner->where('api_id_external', $api_id_external)->first();
    }

    public function getApiPartnerEmailInRowAttribute() {
        $emails = explode(",", $this->api_partner_email);
        $trimmed_emails = array_map(function($v) {
            return trim($v);
        }, $emails);
        return join("\n", $trimmed_emails);
    }

    public function getApiPartnersType($api_type = 'api_request') {

        $hash = [];

        if ($api_type == 'api_request') {

            $result = self::where('api_type', '!=', 'rtbphone')->get();

        } else {
            $result = self::where('api_type', '=', 'rtbphone')->get();

        }

        foreach ($result as $partner) {
            $hash[$partner->api_id_external] = $partner->api_id_external . " - " . $partner->api_name;
        }

        return $hash;
    }


}

