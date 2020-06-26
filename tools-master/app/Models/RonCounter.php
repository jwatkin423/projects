<?php

namespace App\Models;

use App\Models\Campaign;
use Schema;
use DB;

class RonCounter extends BaseModel {

    private $campaign_list;
    /**
     * Checks if a campaign is a phone/ron/targeted campaign
     *  this is called to determine if the campaign has capped
     *
     * @param $campaign     string  name of the campaign
     * @param $redirects    integer total number of redirects
     * @param $status       string current status of the campaign
     * @return array
     */
    public function determineCap($campaign, $redirects, $status) {

        if(!preg_match('/ron$/', $campaign)) {
            return $this->ronCapStatus($this->campaign_list[$campaign]['budget_max'], $this->campaign_list[$campaign]['ron_budget'], $redirects, $status);
        }
        if (isset($this->campaign_list[$campaign]['budget_max'])){
            return $this->ronCapStatus($this->campaign_list[$campaign]['budget_max'], $this->campaign_list[$campaign]['ron_budget'], $redirects, $status);
        }

        return $this->dexCampaignCounterStatus($campaign, $status);
    }

    /*******************************************************
     * Determines status and if the RON campaign is capped
     *
     * @param $campaign_max integer campaign budget max
     * @param $ron_max      integer ron counter
     * @param $redirects    integer total number of redirects
     * @param $status       string  status of the campaign
     * @return array
     ******************************************************/
    private function ronCapStatus($campaign_max, $ron_max, $redirects, $status) {

        if (!$status) {
            $budget_status['label'] = 'label-default';
            $budget_status['status'] = 'Unknown';
        } elseif ($status == 'paused') {
            $budget_status['label'] = 'label-warning';
            $budget_status['status'] = 'Paused';
        } elseif ($redirects >= $campaign_max) {
            $budget_status['label'] = 'label-success';
            $budget_status['status'] = "Capped";
        } elseif ($redirects >= $ron_max) {
            $budget_status['label'] = 'label-success';
            $budget_status['status'] = "ron-capped";
        } else {
            $budget_status['label'] = 'label-info';
            $budget_status['status'] = 'Running';
        }

        return $budget_status;

    }

    /**
     * gets the campaigns that are RON
     *
     * @return array
     */
    public function getBudgetCounter() {
        $campaign_sql_rows = $this->getCampaignsAdertisers();

        foreach ($campaign_sql_rows as $row) {
            $campaign = $row->adv_code . '_' . $row->campaign_code;
            $this->campaign_list[$campaign] = ['budget' => $row->budget_max, 'ron_budget' => $this->getMaxRon($campaign)];
        }

        return $this->campaign_list;
    }

    /**
     * SQL query to get the RON campaigns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function getCampaignsAdertisers() {
        $Campaign = Campaign::select('campaigns.adv_code')
                            ->addSelect('campaigns.campaign_code')
                            ->addSelect('campaigns.budget_max')
                            // ->join('advertisers', 'advertisers.adv_code', '=', 'campaigns.adv_code')
                            ->where('campaign_code', 'like', '%ron')
                            ->get();
        // sd($Campaign);
        return $Campaign;

    }

    private function getMaxRon($campaign) {
        list($adv_code, $campaign_code) = explode('_', $campaign);
        $table_name = "{$campaign}_counter";
        $table_exists = Schema::hasTable($table_name);

        if (!$table_exists){
            $max = DB::table('campaigns')
                     ->select('budget_max')
                     ->where('adv_code', '=', $adv_code)
                     ->where('campaign_code', '=', $campaign_code)
                     ->first();
            $budget_max = $max->budget_max;
        } else {
            $max = DB::table($table_name)
                     ->select(DB::raw("sum(ron_redirects_max) as ron_max"))
                     ->first();
            $budget_max = $max->ron_max;
        }


        return $budget_max;
    }

    public function dexCampaignCounterStatus($campaign, $status) {
        $chunks = explode('_',$campaign);
        $adv_code = $chunks[0];
        $campaign_code = $chunks[1];

        $dex_results = DB::table('campaigns_budget_counter')
                         ->select('budget_now')
                         ->addSelect('budget_max')
                         ->where('adv_code', '=', $adv_code)
                         ->where('campaign_code', '=', $campaign_code)
                         ->first();

        $camp_max = $dex_results->budget_max;
        $redirects = $dex_results->budget_now;

        $ron_max = $camp_max + 1;

        return $this->ronCapStatus($camp_max, $ron_max, $redirects, $status);

    }

}