<?php

namespace App\Models;

class Advertiser extends BaseModel {

    protected $fillable = [
        'adv_code',
        'adv_name',
        'adv_tag1',
        'adv_tag2',
        'adv_type',
        'adv_email',
        'daily_report',
        'status'
    ];
    public $incrementing = false;
    protected $primaryKey = 'adv_code';

    protected $table = 'advertisers';

    public static $rules = [
        'adv_code' => 'required|max:32|alpha_num|unique:advertisers',
        'adv_name' => 'required|max:255',
        'adv_tag1' => 'alpha_num|max:255',
        'adv_tag2' => 'alpha_num|max:255',
        'adv_type' => 'required|in:Adnetwork,Commerce',
        'adv_email' => 'required|email',
        'daily_report' => 'required|in:inactive,active,active_domain',
        'status' => 'required'
    ];

    public function getIdAttribute() {
        return $this->adv_code;
    }

    public function aggDailies() {
        return $this->hasMany('AggDaily', 'adv_code');
    }

    public static function scopeWithoutInactive($query) {
        return $query->where('status', '!=', 'inactive');
    }

    public static function dailyReports() {
        return [
            "inactive" => "No Daily Report",
            "active" => "Send Daily Report",
            "active_domain" => "Send Daily Report w/ Domains"
        ];
    }

    public function advTypes() {
        return ['Adnetwork' => 'Adnetwork', 'Commerce' => 'Commerce', 'Local' => 'Local'];
    }

    public function getAdvEmailInRowAttribute() {
        $emails = explode(",", $this->adv_email);
        $trimmed_emails = array_map(function($v) {
            return trim($v);
        }, $emails);
        return join("\n", $trimmed_emails);
    }


    public static function getAdvertiserLists() {

        $Advertisers = collect(Advertiser::pluck('adv_name', 'adv_code'))->map(function($adv_name, $adv_code) {
            return [$adv_code => $adv_code . ' - ' . $adv_name];
        })->toArray();

        return $Advertisers;
    }

}
