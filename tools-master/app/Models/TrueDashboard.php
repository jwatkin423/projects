<?php

namespace App\Models;

use DB;

class TrueDashboard extends BaseModel {

    public $incrementing = false;

    protected $table = 'v_campaigns_true';
    private $start_date;
    private $end_date;

    public function __construct() {
        $this->start_date = date('Y-m-d', strtotime('-1 week'));
        $this->end_date = date('Y-m-d', strtotime('-1 day'));
    }

    public function trueSummary($rev_code = 'all', $from = null, $to = null) {

        $from = $from != null ? $from : $this->start_date;
        $to = $to != null ? $to : $this->end_date;

        if ($rev_code != 'all') {

            $TrueSummary = TrueDashboard::Select('date_report')
                                        ->addSelect(DB::raw('portal_revenue as revenue'))
                                        ->addSelect(DB::raw('portal_redirects as redirects'))
                                        ->addSelect(DB::raw('our_cost as cost'))
                                        ->where('rev_code', '=', $rev_code);

        } else {

            $TrueSummary = TrueDashboard::Select('date_report')
                                        ->addSelect(DB::raw('SUM(portal_revenue) as revenue'))
                                        ->addSelect(DB::raw('SUM(portal_redirects) as redirects'))
                                        ->addSelect(DB::raw('SUM(our_cost) as cost'));
        }

        $TrueSummary = $TrueSummary->where('date_report', '>=', "$from")
                                   ->where('date_report', '<=', "$to")
                                   ->groupBy('date_report')
                                   ->get();

        return $TrueSummary;

    }

    public function trueSummaryTotal($rev_code, $from = null,  $to = null) {

        $from = $from != null ? $from : $this->start_date;
        $to = $to != null ? $to : $this->end_date;

        if ($rev_code != 'all') {

            $TrueSummaryTotal = TrueDashboard::Select(DB::raw('SUM(portal_revenue) as revenue'))
                                        ->addSelect(DB::raw('SUM(portal_redirects) as redirects'))
                                        ->addSelect(DB::raw('SUM(our_cost) as cost'))
                                        ->where('rev_code', '=', $rev_code);

        } else {

            $TrueSummaryTotal = TrueDashboard::Select(DB::raw('SUM(portal_revenue) as revenue'))
                                        ->addSelect(DB::raw('SUM(portal_redirects) as redirects'))
                                        ->addSelect(DB::raw('SUM(our_cost) as cost'));
        }

        $TrueSummaryTotal = $TrueSummaryTotal->where('date_report', '>=', "$from")
                                   ->where('date_report', '<=', "$to")
                                   ->get();

        return $TrueSummaryTotal;

    }

}


