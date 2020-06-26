<?php
namespace App\Models;

use Merchants;
use DB;

class LogAuctionsNinety extends WarehouseModel {


    protected $table = 'log_auctions_90';

    protected $guarded = ['*'];

    /**
     * ReportController@getTopMerchants
     *
     * @param $start_date           string    start date of the time period
     * @param $end_date             string   end date of the time period
     * @param int $limit            int/string all or select number of results
     * @param null $adv_code        string adv code (i.e. 'cnx')
     * @param null $campaign_code   string campaign code (i.e. us/usron)
     * @return mixed
     *
     **/
    public function topMerchants($start_date, $end_date, $order_by, $limit = 10, $adv_code = null, $campaign_code = null) {

        $adrenalads_db = $this->adrenalads_db;

        $sql = "SELECT
                      a.adv_code,
                      a.campaign_code,
                      b.merchant_id,
                      b.merchant_name,
                      count( * ) as requests,
                      sum( auction_win ),
                      SUM(IF(auction_win, bid, 0)) as cost,
                      SUM(if(auction_win, 1,0)) as redirects,
                      SUM(IF(auction_win, revenue, 0)) as revenue
                    FROM
                      {$this->warehouse_db}.log_auctions_90 a USE index (meta1_val)
                      JOIN $adrenalads_db.{$adv_code}_{$campaign_code}_merchants b ON b.merchant_id = a.meta1_val
                    WHERE
                      adv_code = '$adv_code'
                      AND campaign_code = '$campaign_code'
                      AND date_log >= '$start_date'
                      AND date_log <= '$end_date'
                    GROUP BY
                      meta1_val
                    ORDER BY {$order_by} DESC";

        if ((int)$limit) {
            $sql .= "\nLIMIT $limit";
        }

        $MerchantsTotals = DB::select($sql);


        return $MerchantsTotals;

    }

    /**
     * ReportController@getTopMerchants
     *
     * @param $start_date
     * @param $end_date
     * @param int $limit
     * @param null $adv_code
     * @param null $campaign_code
     * @return $this
     */
    public function topMerchantsRon($start_date, $end_date, $order_by, $limit = 10, $adv_code = null, $campaign_code = null) {

        $campaign_code_ron = $campaign_code . 'ron';
        $adrenalads_db = $this->adrenalads_db;

        $sql = "
                SELECT
                      a.adv_code,
                      a.campaign_code,
                      b.merchant_id,
                      b.merchant_name,
                      count( * ) as requests,
                      sum( auction_win ),
                      SUM(IF(auction_win, bid, 0)) as cost,
                      SUM(if(auction_win, 1,0)) as redirects,
                      SUM(IF(auction_win, revenue, 0)) as revenue
                    FROM
                      {$this->warehouse_db}.log_auctions_90 a USE index (meta1_val)
                      JOIN {$adrenalads_db}.{$adv_code}_{$campaign_code}_merchants b ON b.merchant_id = a.meta1_val
                    WHERE
                      adv_code = '$adv_code'
                      AND campaign_code = '$campaign_code_ron'
                      AND date_log >= '$start_date'
                      AND date_log <= '$end_date'
                    GROUP BY
                      meta1_val
                    ORDER BY {$order_by} DESC";

        if ((int)$limit) {
            $sql .= "\nLIMIT $limit";
        }

        $MerchantsRON = DB::select($sql);

        return $MerchantsRON;
    }

    /**
     * ReportController@getTopMerchants
     *
     * @param $start_date
     * @param $end_date
     * @param int $limit
     * @param $adv_code
     * @param $campaign_code
     * @param $order_by
     * @return mixed
     */
    public function totalTopMerchants($start_date, $end_date, $limit = 10, $adv_code, $campaign_code, $order_by) {

        $campaign_code_ron = $campaign_code . 'ron';
        $adrenalads_db = $this->adrenalads_db;

        $sql = "
                SELECT
                      a.adv_code,
                      a.campaign_code,
                      b.merchant_id,
                      b.merchant_name,
                      count( * ) as requests,
                      sum( auction_win ),
                      SUM(IF(auction_win, bid, 0)) as cost,
                      SUM(if(auction_win, 1,0)) as redirects,
                      SUM(IF(auction_win, revenue, 0)) as revenue
                    FROM
                      {$this->warehouse_db}.log_auctions_90 a USE index (meta1_val)
                      JOIN {$adrenalads_db}.{$adv_code}_{$campaign_code}_merchants b ON b.merchant_id = a.meta1_val
                    WHERE
                      adv_code = '$adv_code'
                      AND (campaign_Code = '$campaign_code' OR campaign_code = '$campaign_code_ron')
                      AND date_log >= '$start_date'
                      AND date_log <= '$end_date'
                    GROUP BY
                      meta1_val
                    ORDER BY {$order_by} DESC";

        if ((int)$limit) {
            $sql .= "\nLIMIT $limit";
        }

        $MerchantsTotals = DB::select($sql);

        return $MerchantsTotals;

    }

    /**
     * ReportController@getMerchantsPerformance
     *
     * @param $start_date
     * @param $end_date
     * @param $merchant_id
     * @param $adv_key
     * @param string $order_by
     * @return mixed
     */
    public function topMerchantsByID($start_date, $end_date, $merchant_id, $adv_key, $order_by = 'date_log') {
        list($adv_code, $campaign_code) = explode('_', $adv_key);
        /*dump($adv_code);
        dd($campaign_code);*/
        $adrenalads_db = $this->adrenalads_db;

        $MerchantsTotals['targeted'] = [];
        $MerchantsTotals['ron'] = [];
        $MerchantsTotals['totals'] = [];

        $MerchantsTotalsRaw = LogAuctionsNinety::select('merchant_name')
                                ->addSelect('date_log')
                                ->addSelect($adrenalads_db . '.' .$adv_key . '_merchants.merchant_id')
                                ->addSelect('log_auctions_90.adv_code')
                                ->addSelect('log_auctions_90.campaign_code')
                                ->addSelect(DB::raw('count(*) as requests'))
                                ->addSelect(DB::raw('SUM(if(auction_win, 1,0)) as redirects'))
                                ->addSelect(DB::raw('SUM(IF(auction_win, bid, 0)) as cost'))
                                ->addSelect(DB::raw('SUM(IF(auction_win, revenue, 0)) as revenue'))
                                ->join($adrenalads_db . '.' .$adv_key . '_merchants', function($join) use($adrenalads_db, $adv_key){
                                      $join->on('log_auctions_90.meta1_val', '=', $adrenalads_db . '.' .$adv_key . '_merchants.merchant_id');
                                })
                                ->where('date_log', '>=', $start_date)
                                ->where('date_log', '<=', $end_date)
                                ->where('merchant_id', '=', $merchant_id)
                                ->where('adv_code', '=', $adv_code)
                                ->whereIn('campaign_code', [$campaign_code, $campaign_code . 'ron'])
                                ->groupBy('date_log')
                                ->groupBy('merchant_name', 'merchant_id', 'campaign_code')
                                ->orderBy($order_by, 'DESC');

//        $MerchantsTotalsRaw = $MerchantsTotalsRaw->toSql();
        $MerchantsTotalsRaw = $MerchantsTotalsRaw->get();
//        dd($MerchantsTotalsRaw);
        foreach ($MerchantsTotalsRaw as $merchant_row) {

            $merchant_name = $merchant_row->merchant_name;
            $adv_code = $merchant_row->adv_code;
            $campaign_code = $merchant_row->campaign_code;
            $date_log = $merchant_row->date_log;
            $requests = $merchant_row->requests;
            $redirects = $merchant_row->redirects;
            $cost = $merchant_row->cost;
            $revenue = $merchant_row->revenue;

            if (isset($MerchantsTotals['totals'][$date_log])) {
                $MerchantsTotals['totals'][$date_log]['requests'] += $requests;
                $MerchantsTotals['totals'][$date_log]['revenue'] += $revenue;
                $MerchantsTotals['totals'][$date_log]['redirects'] += $redirects;
                $MerchantsTotals['totals'][$date_log]['cost'] += $cost;
            } else {
                $MerchantsTotals['totals'][$date_log]['merchant_name'] = $merchant_name;
                $MerchantsTotals['totals'][$date_log]['adv_code'] = $adv_code;
                $MerchantsTotals['totals'][$date_log]['campaign_code'] = $campaign_code;
                $MerchantsTotals['totals'][$date_log]['date_log'] = $date_log;
                $MerchantsTotals['totals'][$date_log]['requests'] = $requests;
                $MerchantsTotals['totals'][$date_log]['revenue'] = $revenue;
                $MerchantsTotals['totals'][$date_log]['redirects'] = $redirects;
                $MerchantsTotals['totals'][$date_log]['cost'] = $cost;
            }

            if (preg_match('/ron$/', $campaign_code)) {
                $MerchantsTotals['ron'][$date_log] = [
                    'merchant_name' => $merchant_name,
                    'adv_code' => $adv_code,
                    'campaign_code' => $campaign_code,
                    'date_log' => $date_log,
                    'requests' => $requests,
                    'redirects' => $redirects,
                    'revenue' => $revenue,
                    'cost' => $cost
                ];
            } else {
                $MerchantsTotals['targeted'][$date_log] = [
                    'merchant_name' => $merchant_name,
                    'adv_code' => $adv_code,
                    'campaign_code' => $campaign_code,
                    'date_log' => $date_log,
                    'requests' => $requests,
                    'redirects' => $redirects,
                    'revenue' => $revenue,
                    'cost' => $cost
                ];
            }

        }

        return $MerchantsTotals;

    }

    /**
     * ReportController@getMerchantsPerformance
     *
     * @param $adv_key
     * @param $start_date
     * @param $end_date
     * @param $merchant_id
     * @return mixed
     */
    public function trueRevenueData($adv_key, $start_date, $end_date, $merchant_id) {

        $table = $adv_key . '_reports';
        list($adv_code, $campaign_code) = explode('_', $adv_key);
        $ron = $campaign_code . 'ron';

        $adrenalads_db = $this->adrenalads_db;

        /** @lang MySQL */
        $sql = "SELECT
                    date_report,
                    campaign_code,
                    sum( revenue ) AS true_rev,
                    sum( cnv ) AS sum_cnv
                FROM
                    {$adrenalads_db}.{$table}
                WHERE
                    date_report >= '$start_date'
                    AND date_report <= '$end_date'
                    AND merchant_id = '$merchant_id'
                    AND adv_code = '$adv_code'
                    AND campaign_code IN ('$campaign_code', '$ron')
                GROUP BY
                    date_report,
                    campaign_code
                 ORDER BY date_report DESC";

        $TrueRevs = DB::select($sql);

        $TrueRevTotals = [];

        foreach ($TrueRevs as $trueRev_row) {

            $campaign_code = $trueRev_row->campaign_code;
            $date_report = $trueRev_row->date_report;
            $true_rev = $trueRev_row->true_rev;
            $sum_cnv = $trueRev_row->sum_cnv;

            if (isset($TrueRevTotals['totals'][$date_report])) {
                $TrueRevTotals['totals'][$date_report]['true_rev'] += $true_rev;
                $TrueRevTotals['totals'][$date_report]['sum_cnv'] += $sum_cnv;

            } else {
                $TrueRevTotals['totals'][$date_report]['campaign_code'] = $campaign_code;
                $TrueRevTotals['totals'][$date_report]['date_report'] = $date_report;
                $TrueRevTotals['totals'][$date_report]['true_rev'] = $true_rev;
                $TrueRevTotals['totals'][$date_report]['sum_cnv'] = $sum_cnv;
            }

            if (preg_match('/ron$/', $campaign_code)) {
                $TrueRevTotals['ron'][$date_report] = [
                    'campaign_code' => $campaign_code,
                    'date_report' => $date_report,
                    'true_rev' => $true_rev,
                    'sum_cnv' => $sum_cnv
                ];
            } else {
                $TrueRevTotals['targeted'][$date_report] = [
                    'campaign_code' => $campaign_code,
                    'date_report' => $date_report,
                    'true_rev' => $true_rev,
                    'sum_cnv' => $sum_cnv
                ];
            }

        }

        return $TrueRevTotals;

    }
}
