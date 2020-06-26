<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use DB;

class Campaign extends BaseModel
{

    public $increment = false;

    protected $table = 'campaigns';

    protected $fillable = [
        'adv_code',
        'campaign_code',
        'campaign_name',
        'campaign_type',
        'campaign_meta',
        'destination_url',
        'redirect_domain',
        'redirect_https',
        'country_code',
        'country_code_alt',
        'est_rev_type',
        'est_rpc',
        'est_rev_multiplier',
        'max_bid_type',
        'max_bid',
        'max_bid_multiplier',
        'budget_min',
        'budget_max',
        'bid_key',
        'use_adult',
        'api_class',
        'status'
    ];

    protected $attributes = [
        'redirect_domain' => 'r.adrenalads.com'
    ];

    public static function boot() {
        parent::boot();
        static::creating(function($campaign) {
            if (is_null($campaign->status)) {
                $campaign->status = 'paused';
            }

            return $campaign;
        });
    }


    public function getCampaignBudgetMax($adv_code, $campaign_code) {
        $campaign_budget_max = $this->select('budget_max')
            ->where('adv_code', '=', $adv_code)
            ->where('campaign_code', '=', $campaign_code)
            ->first();

        return $campaign_budget_max->budget_max ?? 0.0;
    }

    public static function scopeCNX($query)
    {
        return $query->select(['adv_code', 'campaign_code'])
            ->wherein('adv_code', ['cnx', 'pg'])
            ->where('status', '<>', 'inactive')
            ->get();
    }

    public function scopeWithoutInactive($query)
    {
        return $query->where('status', '!=', 'inactive');
    }

    public function find($id, $columns = ['*'])
    {
        $obj = self::fromKey($id);

        return self::where('adv_code', '=', $obj->adv_code)
                   ->where('campaign_code', '=', $obj->campaign_code)
                   ->first();
    }

    public static function fromKey($id) {
        list($adv_code, $campaign_code) = explode('_', $id);

        $obj = new self();
        $obj->adv_code = $adv_code;
        $obj->campaign_code = $campaign_code;

        return $obj;
    }

    protected function setKeysForSaveQuery(Builder $query)
    {
        return $query->where('adv_code', '=', $this->adv_code)
                     ->where('campaign_code', '=', $this->campaign_code);
    }

    public static function propertiesForAlert()
    {
        return [
            'adv_code', 'campaign_code', 'campaign_name', 'destination_url', 'redirect_domain',
            'whitelist_ua', 'whitelist_mobileua', 'graylist_ua', 'graylist_mobileua',
            'country_code', 'est_rpc', 'est_rev_multiplier', 'max_bid', 'max_bid_multiplier',
            'budget_max', 'campaign_type', 'campaign_target', 'status'
        ];
    }

    public function attrsForAlert()
    {
        $attrs = [];
        foreach (self::propertiesForAlert() as $key) {
            $attrs[$key] = $this->$key;
        }
        return $attrs;
    }

    public static function createAlert($name, $existing, $attrs, $original = [])
    {
        $body = "";
        foreach ($attrs as $key => $value) {
            $body .= "{$key}: ";
            if ($existing && isset($original[$key])) {
                $body .= "[{$original[$key]}] => ";
            }
            $body .= "[{$value}]\n";
        }
        $alert = new Alert([
            'status' => 'info',
            'subject' => "Campaign {$name} successfully " . ($existing ? "updated" : "created") . ".",
            'body' => $body
        ]);
        $alert->save();
    }

    public function getName()
    {
        return $this['adv_code'] . '_' . $this['campaign_code'];
    }

    /* Make composite primary key working */
    public function getIdAttribute()
    {
        return $this->adv_code . "_" . $this->campaign_code;
    }

    public function getFormattedMaxBidAttribute($val)
    {
        if ( count($this->max_bid_type) === 1)
        {
            if ($this->max_bid_type[0] === 'bid') {
                return money_format('%.2n', $this->max_bid);
            }
            else {
                return number_format($this->max_bid_multiplier, 2);
            }
        }
        else {
            return [money_format('%.2n', $this->max_bid) , number_format($this->max_bid_multiplier, 2)];
        }
    }

    public function getFormattedEstRevAttribute($val)
    {
        if ($this->est_rev_type === 'rpc')
        {
            return money_format('%.2n', $this->est_rpc);
        }
        else {
            return number_format($this->est_rev_multiplier, 2);
        }
    }

    public function getFormattedBudgetAttribute($val)
    {

        if ($this->budget_type === 'revenue') {
            return money_format('%.2n', $this->budget_max);
        }
        else {
            return number_format($this->budget_max, 0) . ' redirects';
        }

    }

    public static function campaignTypes()
    {
        return [
            "standard" => "Standard",
            "custom" => "Custom"
        ];
    }

    public static function campaignTargets()
    {
        return [
            "domain" => "Domain",
            "feed" => "Feed",
            "RON" => "RON"
        ];
    }

    public function getPrintableCampaignTypeAttribute()
    {
        return self::campaignTypes()[$this->campaign_type];
    }

    public static function userAgents()
    {
        return [
            1 => "Desktop Whitelist",
            2 => "Desktop and Mobile Whitelist",
            3 => "Desktop Whitelist and Graylist",
            4 => "Mobile Whitelist and Graylist",
            5 => "All Traffic"
        ];
    }

    public static function userAgentsMap()
    {
        return [
            1 => [1, 0, 0, 0],
            2 => [1, 1, 0, 0],
            3 => [1, 0, 1, 0],
            4 => [0, 1, 0, 1],
            5 => [1, 1, 1, 1]
        ];
    }

    public function setUserAgentsAttribute($value)
    {
        $map = self::userAgentsMap();
        if (isset($map[$value])) {
            $lists = $map[$value];

            $this->whitelist_ua = $lists[0];
            $this->whitelist_mobileua = $lists[1];
            $this->graylist_ua = $lists[2];
            $this->graylist_mobileua = $lists[3];
        }
    }

    public function getUserAgentsAttribute($value)
    {
        if (is_null($value)) {
            $lists = [
                $this->whitelist_ua,
                $this->whitelist_mobileua,
                $this->graylist_ua,
                $this->graylist_mobileua
            ];
            $user_agents = array_search($lists, self::userAgentsMap());
            if ($user_agents !== false) {
                return $user_agents;
            }
        }
    }

    public function getPrintableUserAgentsAttribute()
    {
        $userAgents = self::userAgents();
        if (isset($userAgents[$this->user_agents])) {
            return $userAgents[$this->user_agents];
        } else {
            return "N/A";
        }
    }

    public function getPlatformAttribute($platform)
    {
        return is_null($platform) ? "all" : $platform;
    }

    public static function countries()
    {
        return [
            "ALL" => "All",
            "INTL" => "INTL",
            "US" => "US",
            "" => "-------"
        ] + Country::all();
    }

    public function getCountryCodeAttribute($country_code)
    {
        return is_null($country_code) ? "ALL" : $country_code;
    }

    public function getCampaignTypeAttribute($value)
    {
        return is_null($value) ? "keyword" : $value;
    }

    public function getUseAdultAttribute($value)
    {
        return is_null($value) ? 0 : $value;
    }

    public function setEstRpcAttribute($value)
    {
        if ( $this->est_rev_type ==='rpc') {
            $this->attributes['est_rpc'] = $value;
            $this->attributes['est_rev_multiplier'] = 0;
        }
    }

    public function setEstRevMultiplierAttribute($value)
    {
        if ($this->est_rev_type === 'rev_multiplier') {
            $this->attributes['est_rev_multiplier'] = $value;
            $this->attributes['est_rpc'] = 0;
        }
    }

    public function getEstRevMultiplierAttribute($value)
    {
        return number_format($value, 2);
    }

    public function getMaxBidTypeAttribute($value)
    {
        if ($value === 'bid-multiplier') {
            return ['bid','multiplier'];
        }

        return [$value];
    }

    public function setMaxBidTypeAttribute($value)
    {
        if (count($value) === 1) {
            $this->attributes['max_bid_type'] = $value[0];
        }
        else {
            $this->attributes['max_bid_type'] = $value[0].'-'.$value[1];
        }
    }

    public function getMaxBidMultiplierAttribute($value)
    {
        return number_format($value, 2);
    }
}
