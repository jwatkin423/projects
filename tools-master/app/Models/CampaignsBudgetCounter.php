<?php
namespace App\Models;

use DB;
use Schema;

/**
 * Class CampaignsBudgetCounter
 * @package App\Models
 * @TODO: extend CapCounter to pass campaign, adv_code, and campaign_code
 * as class scope variables
 */
class CampaignsBudgetCounter extends BaseModel {

    protected $table = 'campaigns_budget_counter';

    protected $fillable = [
        'adv_code',
        'campaign_code',
        'budget_now',
        'budget_max'
    ];

    /**
     * get the max from either table:
     *      <adv_code>_<cc>ron_counter: ron_redirects_max
     *      campagins_budget_max: budget_max
     *      campaigns: budget_max
     *
     *   Returns 0 if nothing is set
     *
     * @param $adv_code         string adv code
     * @param $campaign_code    string campaign code
     * @return bool|mixed
     */
    public function getRonBudgetMax($adv_code, $campaign_code) {
        $ron_budget_max = 0;

        $get_ron_budget_max = $this->getRonData($adv_code, $campaign_code);

        if (!$get_ron_budget_max) {
            $CampaignsBudgetCounter = $this->select('budget_max')
                                           ->where('adv_code', '=', $adv_code)
                                           ->where('campaign_code', '=', $campaign_code)
                                           ->first();

            if (isset($CampaignsBudgetCounter->budget_max)) {
                return $CampaignsBudgetCounter->budget_max;
            } else {
                $Campaign = new Campaign();

                $campaign_budget_max = $Campaign->select('budget_max')
                                                ->where('adv_code', '=', $adv_code)
                                                ->where('campaign_code', '=', $campaign_code)
                                                ->first();

                if (isset($campaign_budget_max->budget_max)) {
                    $ron_budget_max = $campaign_budget_max->budget_max;
                }

            }

        } else {
            $ron_budget_max = $get_ron_budget_max;
        }

        return $ron_budget_max;
    }

    /**
     * if the table exists for the ron counter
     * sum and return ron_redirects_max
     *
     * @param $adv_code
     * @param $campaign_code
     * @return bool
     */
    private function getRonData($adv_code, $campaign_code) {

        $table = "{$adv_code}_{$campaign_code}_counter";

        if (Schema::hasTable($table)) {
            $ron_max_budget = DB::table($table)
                                ->select(DB::raw('SUM(ron_redirects_max) as ron_max'))
                                ->first();

            return $ron_max_budget->ron_max;
        }

        return false;

    }

}
