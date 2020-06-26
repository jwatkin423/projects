<?php
namespace App\Models;

use DB;

class AggCallerDate extends WarehouseModel {

    protected $fillable = [
        'date_log',
        'api_id',
        'adv_code',
        'campaign_code',
        'callers',
        'cost',
        'connects',
        'revenue'
    ];

    protected $table = 'agg_callers_date';


    /************************************
     * Build query for Phone Summary
     *
     * @param $from             \DateTime  Start date
     * @param $to               \DateTime  end date
     * @param $adv_code         string      adv code
     * @param $campaign_code    string      campaign code
     * @param $api_id           integer     API Partner ID
     *
     * @return AggCallerDate Query
     **********************************
     */
    private function chainPhoneSummary($from = null, $to = null, $adv_code= null, $campaign_code = null, $api_id = null) {

        if(is_null($from)) {
            $from = date('Y-m-d', strtotime('10/18/2017'));
        }
        if(is_null($to)) {
            $to = $from;
        }

        $AggCallerDate = AggCallerDate::select('api_id', 'adv_code', 'campaign_code')
                                      ->addSelect(DB::raw('SUM(callers) as callers'))
                                      ->addSelect(DB::raw('SUM(connects) as connects'))
                                      ->addSelect(DB::raw('SUM(revenue) as revenue'))
                                      ->addSelect(DB::raw('SUM(cost) as cost'))
                                      ->where('date_log', '>=', $from)
                                      ->where('date_log', '<=', $to);

        foreach(['adv_code', 'campaign_code', 'api_id'] as $key) {
            if (!is_null($$key) && $$key) {
                $AggCallerDate = $AggCallerDate->where($key, '=', $$key);
            }
        }

        return $AggCallerDate;

    }

    /************************************
     * Phone Summary for Callers
     *
     * @param $date_from        \DateTime   start date
     * @param $date_to          \DateTime   end date
     * @param $adv_code         string      adv code
     * @param $campaign_code    string      campaign code
     * @param $api_id           integer     API Partner ID
     *
     * @return AggCallerDate|\Illuminate\Database\Eloquent\Model|null query
     **********************************
     */
    public function phoneSummary($date_from = null, $date_to = null, $adv_code = null, $campaign_code = null, $api_id = null) {
        $phoneSummary =  $this->chainPhoneSummary($date_from, $date_to, $adv_code, $campaign_code, $api_id)->first();

        return $phoneSummary;
    }

    /************************************
     * Advertiser Summary for Callers
     * @param $from        \DateTime   start date
     * @param $to          \DateTime   end date
     *
     * @return              Mixed     query results
     ************************************/
    public function getPhoneAdvSummary($from = null, $to = null) {
        if(is_null($from)) {
            $from = date('Y-m-d');
        }
        if(is_null($to)) {
            $to = $from;
        }

        return AggCallerDate::select('date_log')
                              ->addSelect('agg_callers_date.adv_code')
                              ->addSelect('agg_callers_date.campaign_code')
                              ->addSelect($this->adrenalads_db . '.campaigns.budget_max')
                              ->addSelect($this->adrenalads_db . '.campaigns.status')
                              ->addSelect(DB::raw("SUM(callers) as callers"))
                              ->addSelect(DB::raw("SUM(cost) as cost"))
                              ->addSelect(DB::raw("SUM(connects) as connects"))
                              ->addSelect(DB::raw("SUM(revenue) as revenue"))
                              ->leftJoin($this->adrenalads_db . '.campaigns', function($join) {
                                  $join->on('agg_callers_date.adv_code', '=', $this->adrenalads_db . '.campaigns.adv_code')
                                       ->on('agg_callers_date.campaign_code', '=', $this->adrenalads_db . '.campaigns.campaign_code');
                              })->where('date_log', '>=', $from)
                              ->where('date_log', '<=', $to)
                              ->orderBy('agg_callers_date.adv_code', 'asc')->orderBy('agg_callers_date.campaign_code', 'asc')
                              ->groupBy('agg_callers_date.adv_code')->groupBy('agg_callers_date.campaign_code')
                              ->get();

    }

    /************************************
     * Advertiser Summary for Callers
     * @param $from        \DateTime   start date
     * @param $to          \DateTime   end date
     *
     * @return              Mixed     query results
     ************************************/
    public function getPhonePartnerSummary($from = null, $to = null) {

        if(is_null($from)) {
            $from = date('Y-m-d');
        }
        if(is_null($to)) {
            $to = $from;
        }

        // get non 0 connects
        $AggCallerDateA = AggCallerDate::select('agg_callers_date.api_id')
                                           ->addSelect($this->adrenalads_db . '.api_partners.api_name')
                                           ->addSelect(DB::raw('SUM(callers) as callers'))
                                           ->addSelect(DB::raw('SUM(connects) as connects'))
                                           ->addSelect(DB::raw('SUM(cost) as cost'))
                                           ->addSelect(DB::raw('SUM(revenue) as revenue'))
                                           ->join($this->adrenalads_db . '.api_partners', 'agg_callers_date.api_id', '=', $this->adrenalads_db . '.api_partners.api_id_external')
                                           ->where('date_log', '>=', $from)
                                           ->where('date_log', '<=', $to)
                                           ->where('campaign_code', '!=', 'all')
                                           ->groupBy('agg_callers_date.api_id');

        // api_ids that are to be ignored for the second part of the union
        $notIn = AggCallerDate::select('api_id')
                                ->where( 'date_log', '>=', $from)
                                ->where('date_log', '<=', $to)
                                ->where('campaign_code', '!=', 'all')
                                ->groupBy('api_id')
                                ->get()->pluck('api_id')->toArray();

        // get connects that are 0
        $AggCallerDateB = AggCallerDate::select('agg_callers_date.api_id')
                                           ->addSelect($this->adrenalads_db . '.api_partners.api_name')
                                           ->addSelect(DB::raw('callers'))
                                           ->addSelect('connects')
                                           ->addSelect('cost')
                                           ->addSelect('revenue')
                                           ->join($this->adrenalads_db . '.api_partners', 'agg_callers_date.api_id', '=', $this->adrenalads_db . '.api_partners.api_id_external')
                                           ->where('campaign_code', '!=', 'all')
                                           ->where('connects', '=', 0)
                                           ->where('date_log', '>=', $from)
                                           ->where('date_log', '<=', $to)
                                           ->whereNotIn('agg_callers_date.api_id', $notIn)
                                           ->orderBy('agg_callers_date.api_id', 'asc')
                                           ->groupBy('agg_callers_date.api_id');


        // create the union
        $AggCallerDate = $AggCallerDateA->union($AggCallerDateB)->get();
        return $AggCallerDate;

    }

    /**
     * @return Campaign
     */
    public function getCampaignAttribute() {
        $campaign = new Campaign();
        $campaign->adv_code = $this->adv_code;
        $campaign->campaign_code = $this->campaign_code;
        return $campaign;
    }

    /**
     * @return mixed Profit (Revenue - Cost)
     */
    public function getProfitAttribute() {
        return $this->revenue - $this->cost;
    }

    /**
     * @return float|int ROI ((profit / cost) * 100)
     */
    public function getRoiAttribute() {
        return $this->cost > 0 ? ($this->profit / $this->cost * 100) : -1;
    }

    /**
     * @return float|int  CPR (Cost / Connects)
     */
    public function getCprAttribute() {
        return $this->connects > 0 ? ($this->cost / $this->connects) : -1;
    }

    /**
     * @return float|int  CPR (Revenue / Connects)
     */
    public function getRprAttribute() {
        return $this->connects > 0 ? ($this->revenue / $this->connects) : -1;
    }

    /**
     * @return float|int  CPR ((Connects / Callers) * 100)
     */
    public function getWinAttribute() {
        return $this->callers > 0 ? ($this->connects / $this->callers * 100) : -1;
    }

    public function getCappedAttribute() {
        $CapCounter = new CapCounter();
        $campaign = $this->adv_code . "_" . $this->campaign_code;
        $cap = $CapCounter->determineCap($campaign, $this->redirects, $this->status);

        return $cap;
    }

}