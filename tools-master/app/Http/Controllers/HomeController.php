<?php
namespace App\Http\Controllers;

use App\Models\AggAuctionsDate;
use App\Models\AggCallerDate;
use App\Models\Alert;
use App\Models\DateFilter;
use App\Models\CapCounter;
use App\Models\ActiveCampaigns;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use phpDocumentor\Reflection\Types\Object_;
use Illuminate\Support\Facades\Log;

class HomeController extends BaseController {

    public function getIndex(Request $request) {

        $data["title"] = "Dashboard";
        $filter = new DateFilter($request->all());
        $today = DateFilter::today();
        $start_date = $today->to;
        $end_date = $today->to;
        $current_date = $today->to;

        if ($request) {
            $today->to = $filter['from'];
            $start_date = $filter['from'];
            $end_date = $filter['to'];
        }

        $data['date_filter'] = $filter;
        $data['today_date_filter'] = $today;
        $data['yesterday_date_filter'] = DateFilter::yesterday();
        $data['month_to_date_date_filter'] = DateFilter::monthToDate();
        $data['last_month_date_filter'] = DateFilter::lastMonth();
        $data['thirty_days_date_filter'] = DateFilter::thirtyDaysAgo();
        $data['last_week_date_filter'] = DateFilter::lastWeek();

        // new dates for the campaigns stats
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['current_date'] = $current_date;

        $AggAuctionsDate = new AggAuctionsDate();

        $ActiveCampaigns  = new ActiveCampaigns();

        $summary = $AggAuctionsDate->getSummary($filter->from, $filter->to);
        $data['summary'] = $summary;

        $partner_summary = $AggAuctionsDate->getPartnerSummary($filter->from, $filter->to);
        $data['partner_summary'] = $partner_summary;

        $partner_traffic = $AggAuctionsDate->getPartnersTraffic($filter->from, $filter->to);
        $data['partners_traffic'] = $partner_traffic;
        $advertisers_summaryV2 = $AggAuctionsDate->getAdvertiserSummary($filter->from, $filter->to);
        $data['advertiser_summary'] = $advertisers_summaryV2;

        $rollup_adv_sums = $this->advRollUp($advertisers_summaryV2);

        foreach ($rollup_adv_sums as $rollup_adv_sum) {
            array_push($advertisers_summaryV2, $rollup_adv_sum);
        }

        usort($advertisers_summaryV2, [$this, 'cmp']);

        $advertisers_summary_compiled = $this->compileRollup($advertisers_summaryV2);
        $data['advertiser_summary_compiled'] = $advertisers_summary_compiled;

        $data['show_budget_status'] = $filter->isToday();
        $data['alerts'] = Alert::important()->get();
        $campaigns_all = $ActiveCampaigns->getActiveCampaigns($today->to);
        $campaigns_active = $campaigns_all['campaigns_active'];
        $campaigns_running = $campaigns_all['campaigns_running'];
        $campaigns_capped = $campaigns_all['campaigns_capped'];
        $campaigns_inactive = $campaigns_all['campaigns_inactive'];

        $data['campaigns_active'] = $campaigns_active;
        $data['campaigns_running'] = $campaigns_running;
        $data['campaigns_capped'] = $campaigns_capped;
        $data['campaigns_inactive'] = $campaigns_inactive['count'];
        $data['campaigns_inactive_list'] = $campaigns_inactive['list'];

        $data['show_legacy'] = $this->checkLegacyView();

        return view('home.index', $data);
    }

    public function getDates(Request $request) {
        $filter = new DateFilter($request->all());

        $data['date_filter'] = $filter;
        $data['today_date_filter'] = DateFilter::today();
        $data['yesterday_date_filter'] = DateFilter::yesterday();
        $data['month_to_date_date_filter'] = DateFilter::monthToDate();
        $data['last_month_date_filter'] = DateFilter::lastMonth();
        $data['thirty_days_filter'] = DateFilter::thirtyDaysAgo();
        $data['last_week_date_filter'] = DateFilter::lastWeek();

        $response = $data;

        return response()->json($response);
    }

    /**
     * summarize for the rollup rows
     * (klk for now)
     * @param $data
     * @return array
     */
    private function advRollUp($data) {
        $rollUp = [];
        $objs = [];

        foreach ($data as $row) {

            if($row->campaign_code !== 'us' && $row->campaign_code !== 'usron' && strtolower($row->campaign_code) !== 'all' && strtolower($row->campaign_code) !== 'direct') {

                if (isset($rollUp[$row->adv_code . '_rollup'])) {
                    $rollUp[$row->adv_code . '_rollup']['auctions'] += $row->auctions;
                    $rollUp[$row->adv_code . '_rollup']['cost'] += $row->cost;
                    $rollUp[$row->adv_code . '_rollup']['revenue'] += $row->revenue;
                    $rollUp[$row->adv_code . '_rollup']['capped'] += $row->capped;
                    $rollUp[$row->adv_code . '_rollup']['profit'] += $row->profit;
                    $rollUp[$row->adv_code . '_rollup']['redirects'] += $row->redirects;
                } else {
                    $rollUp[$row->adv_code . '_rollup']['adv_code'] = $row->adv_code;
                    $rollUp[$row->adv_code . '_rollup']['id'] = $row->adv_code . "__a";
                    $rollUp[$row->adv_code . '_rollup']['auctions'] = $row->auctions;
                    $rollUp[$row->adv_code . '_rollup']['cost'] = $row->cost;
                    $rollUp[$row->adv_code . '_rollup']['revenue'] = $row->revenue;
                    $rollUp[$row->adv_code . '_rollup']['capped'] = $row->capped;
                    $rollUp[$row->adv_code . '_rollup']['profit'] = $row->profit;

                    $rollUp[$row->adv_code . '_rollup']['campaign_code'] = '_';
                    $rollUp[$row->adv_code . '_rollup']['date_log'] = $row->date_log;
                    $rollUp[$row->adv_code . '_rollup']['redirects'] = $row->redirects;
                }

            }

        }

        // calculate ROI/CPR/RPR
        foreach ($rollUp as $index =>$ru) {
            $rollUp[$index]['roi'] = ($rollUp[$index]['profit'] / $rollUp[$index]['cost']) * 100;
            $rollUp[$index]['cpr'] = $rollUp[$index]['cost'] / $rollUp[$index]['redirects'];
            $rollUp[$index]['rpr'] = $rollUp[$index]['revenue'] / $rollUp[$index]['redirects'];
            $rollUp[$index]['win'] = ($rollUp[$index]['redirects'] / $rollUp[$index]['auctions']) * 100;
        }

        foreach ($rollUp as $index => $item) {
            $obj = new \stdClass();
            foreach($item as $key => $val) {
                $obj->{$key} = $val;
            }
            $objs[] = $obj;
        }

        return $objs;
    }

    /**
     * sort array
     *
     * @param $a
     * @param $b
     * @return int|\lt
     */
    function cmp($a, $b) {
        return strcmp($a->id, $b->id);
    }

    /**
     * compile the new summary
     * structure
     *
     * @param $rows
     * @return array
     */
    public function compileRollup($rows) {
        $rollUp = [];

        foreach($rows as $row) {

            if($row->campaign_code === '_'
                || ($row->campaign_code !== 'us'
                && $row->campaign_code !== 'usron'
                && strtolower($row->campaign_code) !== 'all'
                && strtolower($row->campaign_code) !== 'direct')) {

                $row->rollup = true;
                $rollUp[] = $row;

            } else {
                $row->rollup = false;
                $rollUp[] = $row;
            }

        }
        return $rollUp;
    }

    public function ignoreAlerts() {
        Alert::where('status', '=' ,'important')->update(['status' => 'ignore']);

        return redirect('/');
    }

    private function determineCapStatus($advertisers_summary) {

        $CapCounter = new CapCounter();

        foreach($advertisers_summary as $index => $row) {
            $adv_code = $row['adv_code'];
            $campaign_code = $row['campaign_code'];
            $campaign = $adv_code . "_" . $campaign_code;
            $redirects = $row['redirects'];
            $status = $row['status'];

            $cap = $CapCounter->determineCap($campaign, $redirects, $status);

            $advertisers_summary[$index]['label'] = $cap['label'];
            $advertisers_summary[$index]['status'] = $cap['status'];
            $advertisers_summary[$index]['campaign'] = $campaign;
        }

        return $advertisers_summary;

    }

    /**
     * set session to display session variable
     *
     * @param Request $request
     */
    public function setLegacyView(Request $request) {
        $set_session = $request->input('set_session');

        if($set_session === 'start') {
            $time = time();
        } elseif($set_session === 'end') {
            $time = 'NA';
            session()->forget('show_legacy');
        }

        session(['show_legacy' => $time]);

        return response()->json(['time_status' => $time]);
    }

    /**
     * check display session variable
     *
     * @return mixed
     */
    public function checkLegacyView() {

        if(session()->has('show_legacy')) {
            $session_start_time = session('show_legacy');
            $show_status = $this->checkSessionTime($session_start_time);

            return $show_status;
        }

        return false;
    }

    /**
     * return if to show
     * the legacy view
     *
     * @param $time
     * @return bool
     */
    private function checkSessionTime($time) {
        if(is_int($time)) {
            if ((time() - $time) / 60 >= 10) {
                return false;
            }
        } else {

            return false;
        }

        return true;
    }
}
