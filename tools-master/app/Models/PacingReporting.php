<?php
namespace App\Models;

use DB;

abstract class PacingReporting extends WarehouseModel {

    public function getHourlySummary($campaign, $date = null, $all_hours = null) {

        $today = date('Y-m-d');
        if (is_null($date)) {
            $date = $today;
        }

        $reports = $this->select('date_log_hh')
            ->addSelect('date_log')
            ->addSelect(DB::raw("SUM(auctions) as auctions"))
            ->addSelect(DB::raw("SUM(redirects) as redirects"))
            ->addSelect(DB::raw("SUM(revenue) as revenue"))
            ->addSelect(DB::raw('SUM(cost) as cost'))
            ->where('date_log', '=', $date)
            ->where('adv_code', '=', $campaign->adv_code)
            ->where('campaign_code', '=', $campaign->campaign_code)
            ->orderBy('date_log_hh', 'asc')
            ->groupBy('date_log_hh')
            ->get();

        $data = [];
        if ($all_hours == TRUE || (is_null($all_hours) && $date != $today)) {
            for ($i = 0; $i < 24; $i++) {
                $data[$i] = ['auctions' => 0, 'redirects' => 0, 'revenue' => 0, 'cost' => 0];
            }
        }
        foreach($reports as $row) {

            $data[$row->date_log_hh]['auctions'] = $row->auctions;
            $data[$row->date_log_hh]['redirects'] = $row->redirects;
            $data[$row->date_log_hh]['revenue'] = $row->revenue;
            $data[$row->date_log_hh]['cost'] = $row->cost;
        }

        return $data;
    }

    public function getApiPartnerHourlySummary($api_partner, $date = null) {
        $api_id_external = $api_partner->api_id_external;
        $data = [];

        $today = date('y-m-d');
        if (is_null($date)) {
            $date = $today;
        }

        $rows = $this->select('date_log_hh')
            ->addSelect('date_log')
            ->addSelect(DB::raw('SUM(cost) as cost'))
            ->addSelect(DB::raw('SUM(redirects) as redirects'))
            ->addSelect(DB::raw('SUM(auctions) auctions'))
            ->addSelect(DB::raw('SUM(revenue) revenue'))
            ->where('date_log', '=', $date)
            ->where('api_id', '=', $api_id_external)
            ->orderBy('date_log_hh')
            ->groupBy('date_log_hh')
            ->get();

        foreach ($rows as $row) {
            $data[$row->date_log_hh]['auctions'] = $row->auctions;
            $data[$row->date_log_hh]['redirects'] = $row->redirects;
            $data[$row->date_log_hh]['cost'] = $row->cost;
            $data[$row->date_log_hh]['revenue'] = $row->revenue;
        }

        return $data;
    }

    public function getDailySummary($hourly_summary) {
        $total = ['auctions' => 0, 'redirects' => 0, 'cost' => 0, 'revenue' => 0];

        foreach($hourly_summary as $hour) {
            $total['auctions'] += $hour['auctions'];
            $total['redirects'] += $hour['redirects'];
            $total['cost'] += $hour['cost'];
            $total['revenue'] += $hour['revenue'];
        }

        return $total;

    }

}

