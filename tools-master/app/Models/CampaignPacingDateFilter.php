<?php
namespace App\Models;

class CampaignPacingDateFilter extends PacingDateFilter {

    protected $fillable = ['campaign', 'show_full_table', 'advertiser', 'first_date', 'second_date', 'third_date'];

    public static function campaigns() {
        $result = [];

        $campaigns = Campaign::where('status', '!=', 'inactive')
                             ->get();

        foreach ($campaigns as $campaign) {
            $result[$campaign->id] = $campaign->id;
        }

        return $result;
    }


    public function getCampaignAttribute($attr) {
        return $attr ? $attr : 'mojo_superpages';
    }

    public function __toString() {
        return sprintf("%s : [%s, %s, %s]", $this->campaign, $this->first_date, $this->second_date, $this->third_date);
    }

}