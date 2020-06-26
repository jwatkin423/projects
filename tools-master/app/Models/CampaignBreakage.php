<?php

namespace App\Models;

use DB;

class CampaignBreakage extends BaseModel {

    public $incrementing = false;

    protected $table = 'v_campaigns_true';

    protected $fillable = [
        'date_report',
        'rev_code',
        'portal_redirects',
        'portal_revenue',
        'our_redirects',
        'our_revenue',
        'breakage_rd',
        'breakage_rev'
    ];

    /**
     * Gets the raw breakage from the database
     *
     * @param $rev_code string
     * @param $dates array
     * @return mixed
     */
    public function getBreakages($rev_code, $dates) {

        if ($rev_code != 'all') {
            $breakages = CampaignBreakage::select('*')
            ->where('rev_code', $rev_code);
        } else {
            $breakages = CampaignBreakage::select('date_report')
                 ->addSelect(DB::raw('SUM(portal_redirects) as portal_redirects'))
                 ->addSelect(DB::raw('SUM(portal_revenue) as portal_revenue'))
                 ->addSelect(DB::raw('SUM(our_redirects) as our_redirects'))
                 ->addSelect(DB::raw('SUM(our_revenue) as our_revenue'))
                 ->addSelect(DB::raw('SUM(breakage_rd) as breakage_rd'))
                 ->addSelect(DB::raw('SUM(breakage_rev) as breakage_rev'));
        }

        $breakages = $breakages->where('date_report', '>=', $dates['from'])
                ->where('date_report', '<=', $dates['to'])
                ->groupBy('date_report')
                ->get();

        return $breakages;

    }
    public function getBreakageSummationByDate($rev_code, $dates) {

        $results = [];

        $breakages = new CampaignBreakage();

        $breakages = $breakages::select(DB::raw('SUM(portal_redirects) as portal_redirects'))
                         ->addSelect(DB::raw('SUM(portal_revenue) as portal_revenue'))
                         ->addSelect(DB::raw('SUM(our_redirects) as our_redirects'))
                         ->addSelect(DB::raw('SUM(our_revenue) as our_revenue'))
                         ->addSelect(DB::raw('SUM(breakage_rd) as breakage_rd'))
                         ->addSelect(DB::raw('SUM(breakage_rev) as breakage_rev'));

        if ($rev_code != 'all') {
            $breakages =  $breakages->where('rev_code', $rev_code);
        }

        $breakages = $breakages->where('date_report', '>=', $dates['from'])
                               ->where('date_report', '<=', $dates['to'])
                               ->first();

        if ($breakages->our_redirects !== NULL && $breakages->our_revenue !== NULL && $breakages->portal_redirects !== NULL && $breakages->portal_revenue) {
            $br_red = ($breakages->our_redirects - $breakages->portal_redirects) / $breakages->our_redirects;
            $br_rev = ($breakages->our_revenue - $breakages->portal_revenue) / $breakages->our_revenue;
            $br = $this->calculateBreakageRevPercentage($br_red);
            $breakages->breakage_rd = $br;
            $breakages->breakage_rev = $br_rev;

            $results = $breakages;
        }

        return $results;

    }


    /**
     * Loops through the array to calculate the breakage by
     * date
     *
     * @param $breakages
     * @return array
     */
    public function breakageByDate($breakages) {

        $breakageByDate = collect($breakages)
            ->mapWithKeys(function($item) {
                return [$item->date_report => [
                    'b_rev' => $this->calculateBreakageRevPercentage($item->breakage_rev),
                    'b_rd' => $item->breakage_rd
                    ]
                ];

            })
            ->toArray();

        return $breakageByDate;
    }


    /**
     * Loops through the dates and calculates the breakage
     * and returns an array of arrays to be converted into
     * JSON for the true summary view graphs
     *
     * @param $breakages
     * @return array
     */
    public function breakage($breakages) {
        $percent = [];
        $absolute = [];
        $revenue = [];
        $rev_diff = [];

        foreach ($breakages as $breakage) {
            $bp = round((100 * $breakage->breakage_rd), 2, PHP_ROUND_HALF_UP);
            $timestamp = DateFilter::phpToJS($breakage->date_report);
            $portal_revenue = $breakage->portal_revenue;
            $adren_revenue = $breakage->our_revenue;
            $absolute_breakage = $breakage->our_redirects - $breakage->portal_redirects;
            $rp = $this->calculateBreakageRevPercentage($breakage->breakage_rev);

            $percent[] = [$timestamp, $bp];
            $absolute['diff'][] = [$timestamp, $absolute_breakage];
            $revenue['portal'][] = [$timestamp, $portal_revenue];
            $revenue['adren'][] = [$timestamp, $adren_revenue];
            $rev_diff['percent'][] = [$timestamp, $rp];
        }

        return ['percent' => $percent, 'absolute' => $absolute, 'revenue' => $revenue, 'rev_diff' => $rev_diff];
    }

    private function calculateBreakageRevPercentage($breakage_rev) {
        $rp = round((100 * $breakage_rev), 2, PHP_ROUND_HALF_UP);

        return $rp;
    }

}