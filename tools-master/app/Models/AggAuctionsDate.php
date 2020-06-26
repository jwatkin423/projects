<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\VarDumper;

class AggAuctionsDate extends WarehouseModel {

    private $our_profit;

    public $label;
    public $capped_status;

    protected $fillable = [
        'date_log',
        'api_id',
        'adv_code',
        'campaign_code',
        'cost',
        'redirects',
        'revenue'
    ];

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        return $this->fill($attributes);
    }

    protected $table = 'agg_auctions_date';

    public static function today() {
        return date('Y-m-d');
    }

    public static function randomRequests() {
        return rand(100, 500);
    }

    public static function randomCost() {
        return rand(10000, 30000)/100;
    }

    public static function randomRevenue() {
        //from 110% to 160% from random cost
        return self::randomCost()*(rand(110, 160)/100);
    }

    public static function randomRedirects() {
        return rand(50, 200);
    }

    protected function getSummaryChain($from = null, $to = null, $adv_code = null, $campaign_code = null, $api_id = null) {

        if(is_null($from)) {
            $from = date('Y-m-d', now());
        }
        if(is_null($to)) {
            $to = $from;
            $to = $from;
        }
        /*$ecn = null;

        // This query is separated out due to the cut for Tamir
        if ($adv_code === null && $campaign_code === null && $api_id == null) {
            $ecn = AggAuctionsDate::on($this->connection)->select('date_log')
                          ->addSelect(DB::raw("SUM(cost) as cost"))
                          ->addSelect(DB::raw("SUM(redirects) as redirects"))
                          ->addSelect(DB::raw("SUM(revenue) as revenue"))
                          ->addSelect(DB::raw("SUM(auctions) as auctions"))
                          ->where('date_log', '>=', $from)
                          ->where('date_log', '<=', $to)
                          ->whereIn('adv_code', ['ecn', 'ecn2'])->first();

            // calculate the total revenue
            $profit = $ecn['revenue'] - $ecn['cost'];
            $this->our_profit = .6 * $profit;
            $this->our_profit += $ecn['cost'];
        }*/

        // default query
        $chain = AggAuctionsDate::on($this->connection)->select('date_log')
                                ->addSelect(DB::raw("SUM(cost) as cost"))
                                ->addSelect(DB::raw("SUM(redirects) as redirects"))
                                ->addSelect(DB::raw("SUM(revenue) as revenue"))
                                ->addSelect(DB::raw("SUM(auctions) as auctions"))
                                ->where('date_log', '>=', $from)
                                ->where('date_log', '<=', $to)
                                ->whereNotIn('adv_code', ['ecn', 'ecn2'])
                                ->where('campaign_code', '!=', 'all');

        // create additional where clauses based params passed
        foreach(['adv_code', 'campaign_code', 'api_id'] as $key) {
            if( !is_null($$key) && $$key ) {
                $chain = $chain->where($key, '=', $$key);
            }
        }

        return $chain;
    }

    public function getSummary($from = null, $to = null, $adv_code = null, $campaign_code = null, $api_id = null) {
        $results = $this->getSummaryChain($from, $to, $adv_code, $campaign_code, $api_id);
        $total_results = $results->first();

        $total_cost = $total_results['cost']/* +  $results['ecn']['cost']*/;
        $total_redirects = $total_results['redirects']/* +  $results['ecn']['redirects']*/;
        $total_revenue = $total_results['revenue'] + $this->our_profit;
        $total_auctions = $total_results['auctions']/* +  $results['ecn']['auctions']*/;

        $total_results['cost'] = $total_cost;
        $total_results['redirects'] = $total_redirects;
        $total_results['revenue'] = $total_revenue;
        $total_results['auctions'] = $total_auctions;

        return $total_results;
    }

    /**
     * @param null $from
     * @param null $to
     * @param null $adv_code
     * @param null $campaign_code
     * @param null $api_id
     * @return array
     */
    public function getDailySummary($from = null, $to = null, $adv_code = null, $campaign_code = null, $api_id = null) {

        $reports = $this->getSummaryChain($from, $to, $adv_code, $campaign_code, $api_id);
        $dailySummary = $reports->orderBy('date_log', 'asc')->groupBy('date_log')->get();

        $data = [];
        foreach($dailySummary as $row) {
            $data[$row['date_log']]['auctions'] = $row->auctions;
            $data[$row['date_log']]['cost'] = $row->cost;
            $data[$row['date_log']]['redirects'] = $row->redirects;
            $data[$row['date_log']]['revenue'] = $row->revenue;
        }

        return $data;
    }

    /**
     * @param null $from
     * @param null $to
     * @return mixed
     */
    public function getPartnerSummary($from = null, $to = null) {
        $temp = [];
        $join_type = null;

        if(is_null($from)) {
            $from = $this->today();
        }
        if(is_null($to)) {
            $to = $from;
        }

        if ($to == $from && $from == $this->today()) {
            $join_type = 'left';
        }

        $adrenalads_db = $this->adrenalads_db;
        $warehouse_db = $this->warehouse_db;

        $sql = "select
                b.api_id,
                a.api_id_external,
                a.api_name,
                b.auctions,
                b.redirects,
                b.cost,
                b.revenue
            from $adrenalads_db.api_partners a
            left join (
                    SELECT
                        api_id,
                        SUM(auctions) as auctions,
                        SUM(redirects) as redirects,
                        SUM(cost) as cost,
                        SUM(revenue) as revenue
                    FROM $warehouse_db.agg_auctions_date
                    WHERE
                        date_log >= '$from'
                        AND date_log <= '$to'
                        GROUP BY api_id
            ) b ON b.api_id = a.api_id_external
            WHERE
                a.status = 'pr'
                AND b.api_id IS NOT NULL
            group by
                a.api_id_external
            ORDER BY
                a.api_name";
        $AdvertisersSummary = DB::select($sql);

        foreach ($AdvertisersSummary as $row) {

            $this->auctions = $row->auctions;
            $this->redirects = $row->redirects;
            $this->cost = $row->cost;
            $this->revenue = $row->revenue;

            $ApiPartner = new ApiPartner((array) $row);
            $ApiPartner->auctions = $this->auctions;
            $ApiPartner->redirects = $this->redirects;
            $ApiPartner->cost = $this->cost;
            $ApiPartner->revenue = $this->revenue;

            $ApiPartner->win = $this->getWinAttribute();
            $ApiPartner->roi = $this->getRoiAttribute();
            $ApiPartner->profit = $this->getProfitAttribute();
            $ApiPartner->cpr = $this->getCprAttribute();
            $ApiPartner->rpr = $this->getrprAttribute();

            $temp[] = $ApiPartner;

        }

        return $temp;
    }

    public function getPartnersTraffic($from = null, $to = null) {
        if ($from == null) {
            $from = $this->today();
        }

        if ($to == null) {
            $to = $this->today();
        }

        $adrenalads_db = $this->adrenalads_db;
        $warehouse_db = $this->warehouse_db;

        $sql = "select
                b.api_id,
                a.api_id_external,
                a.api_name,
                b.auctions,
                b.redirects,
                b.cost,
                b.revenue
            from {$adrenalads_db}.api_partners a
            left join (
                    SELECT
                        api_id,
                        SUM(auctions) as auctions,
                        SUM(redirects) as redirects,
                        SUM(cost) as cost,
                        SUM(revenue) as revenue
                    FROM {$warehouse_db}.agg_auctions_date
                    WHERE
                        date_log >= '$from'
                        AND date_log <= '$to'
                        GROUP BY api_id
            ) b ON b.api_id = a.api_id_external
            WHERE
                a.status = 'pr'
                -- AND b.api_id IS NOT NULL    -- **
                AND a.api_type <> 'rtbphone'   -- **
                AND a.api_id >= 3               -- **
            group by
                a.api_id_external
            ORDER BY
                a.api_id_external";

        $PartnersTraffic = DB::select($sql);

        $zeroTraffic = 0;
        $partners = [];
        $running_partners = [];
        foreach($PartnersTraffic as $row) {
            $redirecs = $row->redirects;
            $auctions = $row->auctions;

            if (($auctions == NULL || $auctions == 0) || ($auctions && ($redirecs == NULL || $redirecs == 0))) {
                $zeroTraffic++;
                $partners[] = $row->api_name;
            } else {
                $running_partners[] = $row->api_name;
            }

        }

        return ['zero' => $zeroTraffic, 'zt_partners' => $partners, 'running_partners' => $running_partners];
    }

    public function getAdvertiserSummary($from = null, $to = null) {
        if ($from == null) {
            $from = $this->today();
        }

        if ($to == null) {
            $to = $this->today();
        }

        $adrenalads_db = $this->adrenalads_db;
        $warehouse_db = $this->warehouse_db;

        $sql = "
            SELECT
                a.date_log,
                c.adv_code,
                c.campaign_code,
                CONCAT( c.adv_code, '_', c.campaign_code ) AS id,
                c.budget_max,
                c.status,
                SUM( a.auctions ) AS auctions,
                SUM( a.redirects ) AS redirects,
                SUM( a.cost ) AS cost,
                SUM( a.revenue ) AS revenue
            FROM
                {$warehouse_db}.agg_auctions_date a
            LEFT JOIN
                {$adrenalads_db}.campaigns c ON (a.adv_code = c.adv_code AND a.campaign_code = c.campaign_code)
            WHERE
                ( date_log >= '{$from}' AND date_log <= '{$to}' )
                AND c.campaign_code != 'phone'
                and (a.auctions > 0    OR c.status IN ('active', 'paused'))
            GROUP BY
                adv_code,
                campaign_code
            UNION
            SELECT
                date_log,
                adv_code,
                campaign_code,
                CONCAT( adv_code, '_', campaign_code ) AS id,
                '0.0' AS budget_max,
                'warehouse' AS status,
                SUM( auctions ) AS auctions,
                SUM( redirects ) AS redirects,
                SUM( cost ) AS cost,
                SUM( revenue ) AS revenue
            FROM
                {$warehouse_db}.agg_auctions_date
            WHERE
                ( date_log >= '{$from}' AND date_log <= '{$to}' )
            GROUP BY
                adv_code,
                campaign_code
            HAVING
                id NOT IN (
                    SELECT adv_key FROM v_adv_keys WHERE campaign_code != 'phone' AND status IN ( 'active', 'paused' )
                )";

        $AggAuctionsDate = DB::select($sql);

        foreach($AggAuctionsDate as $row) {
            $campaign = $row->id;
            $redirects = $row->redirects;
            $status = $row->status;
            $CapCounter = new CapCounter();
            $cap = $cap = $CapCounter->determineCap($campaign, $redirects, $status);

            $row->capped['status'] = $cap['status'];
            $row->capped['label'] = $cap['label'];
            $row->win = $row->auctions > 0 ? ($row->redirects / $row->auctions * 100) : 0;
            $row->profit = $row->revenue - $row->cost;
            $row->roi = $row->cost > 0 ? ($row->profit / $row->cost * 100) : -1;
            $row->cpr = $row->redirects > 0 ? ($row->cost / $row->redirects) : -1;
            $row->rpr = $row->redirects > 0 ? ($row->revenue / $row->redirects) : -1;

        }

        return $AggAuctionsDate;


    }

    public function getCampaignAttribute() {
        $campaign = new Campaign();
        $campaign->adv_code = $this->adv_code;
        $campaign->campaign_code = $this->campaign_code;
        return $campaign;
    }

    public function getProfitAttribute() {
        return $this->revenue - $this->cost;
    }

    public function getRoiAttribute() {
        return $this->cost > 0 ? ($this->profit / $this->cost * 100) : -1;
    }

    public function getCprAttribute() {
        return $this->redirects > 0 ? ($this->cost / $this->redirects) : -1;
    }

    public function getRprAttribute() {
        return $this->redirects > 0 ? ($this->revenue / $this->redirects) : -1;
    }

    public function getWinAttribute() {

        return $this->auctions > 0 ? ($this->redirects / $this->auctions * 100) : -1;
    }

    public function getCappedAttribute() {
        $CapCounter = new CapCounter();
        $campaign = $this->adv_code . "_" . $this->campaign_code;
        $cap = $CapCounter->determineCap($campaign, $this->redirects, $this->status);

        return $cap;
    }

    public function getBudgetStatusAttribute() {

        if (!$this->status) {
            $budget_status['label'] = 'label-default';
            $budget_status['status'] = 'Unknown';
        }
        elseif ($this->status == 'paused') {
            $budget_status['label'] = 'label-warning';
            $budget_status['status'] = 'Paused';
        }
        elseif (
            $this->budget_type == 'revenue' && ($this->revenue >= $this->budget_max) ||
            $this->budget_type == 'redirects' && ($this->redirects >= $this->budget_max)
        ) {
            $budget_status['label'] = 'label-success';
            $budget_status['status'] = 'Capped';
        }
        else {
            $budget_status['label'] = 'label-info';
            $budget_status['status'] = 'Running';
        }

        return $budget_status;
    }

    public function getRpmAttribute() {
        if($this->date_report == date('Y-m-d')) {
            $minutes = date('H') * 60 + date('i');
        } else {
            $minutes = 60 * 24;
        }
        if (!$minutes ) {
            return 0;
        } else {
            return $this->redirects / $minutes;
        }
    }

}
