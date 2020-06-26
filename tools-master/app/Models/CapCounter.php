<?php
namespace App\Models;

class CapCounter extends BaseModel {

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

        $campaign_details = $this->getMaxBudget($campaign);

        return $this->ronCapStatus($campaign_details[$campaign]['campaign_max'], $campaign_details[$campaign]['ron_max'], $redirects, $status);

    }

    /**
     * gets the max budget for the campaign
     *
     * @param $campaign     string name of the campaign
     * @return mixed
     */
    private function getMaxBudget($campaign) {
        list($adv_code, $campaign_code) = explode('_', $campaign);

        $CampaignsBudgetCounter = new CampaignsBudgetCounter();
        $Campaign = new Campaign();

        $ron_max = $CampaignsBudgetCounter->getRonBudgetMax($adv_code, $campaign_code);

        $campaign_budget_max = $Campaign->getCampaignBudgetMax($adv_code, $campaign_code);

        $campaign_details[$campaign]['campaign_max'] = $campaign_budget_max;
        $campaign_details[$campaign]['ron_max'] = $ron_max;

        return $campaign_details;
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


}