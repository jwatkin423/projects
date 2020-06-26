<?php

namespace App\Http\Controllers;

use App\Models\APIPartnerPacingDateFilter;
use App\Models\CampaignBreakage;
use App\Models\CampaignPacingDateFilter;
use App\Models\MerchantBreakdown;
use App\Models\MerchantRPCArchive;
use App\Models\Merchants;
use App\Models\PacingDateFilter;
use App\Models\AggAuctionsDate;
use App\Models\AggAuctionsHour;
use App\Models\DateFilter;
use App\Models\RevenueCodes;
use App\Models\TrafficDashboardFilter;
use App\Models\TrafficBreakdownFilter;
use App\Models\Campaign;
use App\Models\CommerceAccounts;
use Illuminate\Http\Request;
use App\Models\TrueDashboard;
use App\Models\LogAuctionsNinety;
use App\Models\WarehouseModel;
use DB;

class ReportController extends BaseController {

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPacing(Request $request) {
        $data['title'] = "Pacing";
        $dates = $request->except('token');
        $filter = new CampaignPacingDateFilter($dates);
        $data['date_filter'] = $filter;
        $data['campaigns'] = $filter->campaigns();

        $data['today_date_filter'] = DateFilter::today();
        $data['yesterday_date_filter'] = DateFilter::yesterday();
        $data['one_week_filter'] = CampaignPacingDateFilter::minusOneWeek();
        $data['two_weeks_filter'] = CampaignPacingDateFilter::minusTwoWeeks();
        $data['day_before_filter'] = CampaignPacingDateFilter::dayBefore();
        $data['month_prior_filter'] = CampaignPacingDateFilter::monthPrior();

        $campaign = Campaign::fromKey($filter->campaign);
        $summaries = [];

        $AggAuctionsHour = new AggAuctionsHour();

        $summaries['hourly']['first_date'] = $AggAuctionsHour->getHourlySummary($campaign, $filter->first_date);
        $summaries['hourly']['second_date'] = $AggAuctionsHour->getHourlySummary($campaign, $filter->second_date);
        $summaries['hourly']['third_date'] = $AggAuctionsHour->getHourlySummary($campaign, $filter->third_date);

        $summaries['daily']['first_date'] = $AggAuctionsHour->getDailySummary($summaries['hourly']['first_date']);
        $summaries['daily']['second_date'] = $AggAuctionsHour->getDailySummary($summaries['hourly']['second_date']);
        $summaries['daily']['third_date'] = $AggAuctionsHour->getDailySummary($summaries['hourly']['third_date']);

        $data['summaries'] = $summaries;

        return view('reports.pacing', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAPIPartnerPacing(Request $request) {

        $data['title'] = "API Partner Pacing";
        $dates = $request->except('_token');
        $filter = new APIPartnerPacingDateFilter($dates);
        $api_partner = $filter->api_partner;

        $data['date_filter'] = $filter;
        $data['api_partners'] = $filter->api_partners();

        $data['today_date_filter'] = DateFilter::today();
        $data['yesterday_date_filter'] = DateFilter::yesterday();
        $data['one_week_filter'] = APIPartnerPacingDateFilter::minusOneWeek();
        $data['two_weeks_filter'] = APIPartnerPacingDateFilter::minusTwoWeeks();
        $data['day_before_filter'] = APIPartnerPacingDateFilter::dayBefore();
        $data['month_prior_filter'] = APIPartnerPacingDateFilter::monthPrior();

        $AggAuctionsHour = new AggAuctionsHour();
//        $DomainReporting = new DomainReporting();

        $summaries = [];

        $summaries['hourly']['first_date'] = $AggAuctionsHour->getApiPartnerHourlySummary($api_partner, $filter->first_date);
        $summaries['hourly']['second_date'] = $AggAuctionsHour->getApiPartnerHourlySummary($api_partner, $filter->second_date);
        $summaries['hourly']['third_date'] = $AggAuctionsHour->getApiPartnerHourlySummary($api_partner, $filter->third_date);

        $summaries['daily']['first_date'] = $AggAuctionsHour->getDailySummary($summaries['hourly']['first_date']);
        $summaries['daily']['second_date'] = $AggAuctionsHour->getDailySummary($summaries['hourly']['second_date']);
        $summaries['daily']['third_date'] = $AggAuctionsHour->getDailySummary($summaries['hourly']['third_date']);

        $data['summaries'] = $summaries;

        return view('reports.apipartnerpacing', $data);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDates(Request $request) {
        $dates = $request->except('token');
        $filter = new DateFilter($dates);

        $data['date_filter'] = $filter;
        $data['today_date_filter'] = PacingDateFilter::today();
        $data['yesterday_date_filter'] = PacingDateFilter::yesterday();
        $data['one_week_filter'] = PacingDateFilter::minusOneWeek();
        $data['two_weeks_filter'] = PacingDateFilter::minusTwoWeeks();
        $data['day_before_filter'] = PacingDateFilter::dayBefore();
        $data['month_prior_filter'] = PacingDateFilter::monthPrior();

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTrafficDashboard(Request $request) {
        $AggAuctionsDate = new AggAuctionsDate();

        $data['title'] = "Traffic";
        $dates = $request->except('_token');
        $data['date_filter'] = $date_filter = new DateFilter($dates);

        $today = date(DateFilter::getFormat(), time());
        $yesterday = date(DateFilter::getFormat(), time() - 60 * 60 * 24);
        $week_ago = date(DateFilter::getFormat(), time() - 60 * 60 * 24 * 7);
        $data['today_date_filter'] = DateFilter::today();
        $data['yesterday_date_filter'] = new DateFilter(['from' => $yesterday, 'to' => $today]);
        $data['week_ago_date_filter'] = new DateFilter(['from' => $week_ago, 'to' => $today]);

        $start_date = $dates['start_date'] ?? date('Y-m-d', time());
        $end_date = $dates['end_date'] ?? date('Y-m-d', time());

        $yesterday_date_filter = DateFilter::yesterday();
        $month_to_date_date_filter = DateFilter::monthToDate();
        $last_month_date_filter = DateFilter::lastMonth();
        $last_week_date_filter = DateFilter::lastWeek();
        $thirty_days_filter = DateFilter::thirtyDaysAgo();

        $data['filter'] = $filter = new TrafficDashboardFilter($dates);
        $adv_code = $campaign_code = null;
        if ($request->get('campaign') !== null && $request->get('campaign') !== 'ALL') {
            list($adv_code, $campaign_code)  = explode('_', $request->get('campaign'));
        }


        $data['advertisers'] = TrafficDashboardFilter::advertisers();
        $data['campaigns'] = TrafficDashboardFilter::campaigns();
        $data['inventories'] = TrafficDashboardFilter::inventories();

        $data['summary'] = $AggAuctionsDate->getSummary(
            $start_date, $end_date, $adv_code,
            $campaign_code, $filter->inventory
        );

        //Minimal length of summary is week
        $week_from_to_stamp = strtotime($end_date) - 60 * 60 * 24 * 7;
        $week_from_to = date(DateFilter::getFormat(), $week_from_to_stamp);

        $data['daily_summary'] = $AggAuctionsDate->getDailySummary(
            $start_date,
            $end_date,
            $adv_code,
            $campaign_code,
            $filter->inventory);

        $prepared = [];
        foreach (['auctions', 'redirects', 'revenue', 'cost'] as $key) {
            $prepared[$key] = [];

            foreach ($data['daily_summary'] as $day => $row) {
                $timestamp = strtotime($day . " UTC") * 1000;
                $prepared[$key][] = [$timestamp, $row[$key]];
            }
        }
        $data['prepared_daily_summary'] = $prepared;

        return view('reports.traffic', $data)
            ->with('filter', $filter)
            ->with('start_date', $start_date)
            ->with('end_date', $end_date)
            ->with('yesterday_date_filter', $yesterday_date_filter)
            ->with('month_to_date_date_filter', $month_to_date_date_filter)
            ->with('last_week_date_filter', $last_week_date_filter)
            ->with('thirty_days_date_filter', $thirty_days_filter)
            ->with('last_month_date_filter', $last_month_date_filter);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function getTrafficDistribution(Request $request) {
        $AggAuctionsDate = new AggAuctionsDate();
        $WareHouse = new WarehouseModel();
        $adrenalads_db = $WareHouse->getAdrenaladsConnection();

        $data['title'] = "Breakdown";
        $dates = $request->except('token');
        $data['date_filter'] = $date_filter = new DateFilter($dates);

        $yesterday = date(DateFilter::getFormat(), time() - 60 * 60 * 24);
        $week_ago = date(DateFilter::getFormat(), time() - 60 * 60 * 24 * 7);
        $data['today_date_filter'] = DateFilter::today();
        $data['yesterday_date_filter'] = new DateFilter(['from' => $yesterday, 'to' => $yesterday]);
        $data['week_ago_date_filter'] = new DateFilter(['from' => $week_ago, 'to' => $week_ago]);

        $data['filter'] = $filter = new TrafficBreakdownFilter($dates);
        $data['partners'] = TrafficBreakdownFilter::partners();
        $data['advertisers'] = TrafficBreakdownFilter::activeCampaigns();

        $aggregate = $AggAuctionsDate::where('date_log', '>=', $date_filter->from)
                                     ->where('date_log', '<=', $date_filter->to);

        if ($filter->traffic == 'advertiser') {
            $aggregate = $aggregate->select(DB::raw("api_id_external, api_name, adv_code, agg_auctions_date.api_id, campaign_code, sum(auctions) as auctions, sum(redirects) as redirects, sum(revenue) as revenue"))
                                   ->groupBy('api_id_external')
                                   ->join($adrenalads_db . '.api_partners', 'agg_auctions_date.api_id', '=', $adrenalads_db . '.api_partners.api_id_external');
            if (strlen($filter->advertiser) > 0) {
                $aggregate = $aggregate->where('adv_code', $filter->adv_code)
                                       ->where('campaign_code', $filter->campaign_code);
            }
        }

        if ($filter->traffic == 'partner') {
            $aggregate = $aggregate->select(DB::raw(" adv_code, api_id, campaign_code, sum(auctions) as auctions, sum(redirects) as redirects, sum(revenue) as revenue"))
                                   ->groupBy("adv_code")
                                   ->groupBy("campaign_code");
            if (strlen($filter->partner) > 0) {
                $aggregate = $aggregate->where('agg_auctions_date.api_id', $filter->partner);
            }
        }
        $data['aggregate'] = $aggregate->get();
        $data['total_requests'] = 0;
        $data['total_redirects'] = 0;
        $data['total_revenue'] = 0;
        foreach ($data['aggregate'] as $row) {
            $data['total_requests'] += $row->requests;
            $data['total_redirects'] += $row->redirects;
            $data['total_revenue'] += $row->revenue;
        }

        return view('reports.distribution', $data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getTrafficBreakage(Request $request) {
        $title = "Breakage";

        $CampaignBreakages = new CampaignBreakage();
        $rev_code_submitted = $request->get('rev_code');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');

        $rev_code = $rev_code_submitted == null ? 'cnx_us' : $rev_code_submitted;

        $dates['from'] = $date_from == null ? date(DateFilter::getFormat(), time()) : $date_from;
        $dates['to'] = $date_to == null ? date(DateFilter::getFormat(), time()) : $date_to;

        $date_filter = new DateFilter($dates);
        $breakages = $CampaignBreakages->getBreakages($rev_code, $dates);

        $rev_codes = $this->getRevCodes();

        $breakage = $CampaignBreakages->breakage($breakages);

        return view('reports.breakage')
            ->with('title', $title)
            ->with('rev_code', $rev_code)
            ->with('rev_codes', $rev_codes)
            ->with('br', $breakage)
            ->with('date_filter', $date_filter)
            ->with('breakages', $breakages)
            ->with('current_date_from', $dates['from'])
            ->with('current_date_to', $dates['to']);

    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBreakageDates() {
        $DateFilter = new DateFilter();
        $week_ago = date(DateFilter::getFormat(), time() - 60 * 60 * 24 * 7);

        $data['week_ago_filter'] = ['from' => $week_ago, 'to' => date(DateFilter::getFormat(), time())];
        $data['thirty_day_filter'] = $DateFilter->thirtyDays();
        $data['sixty_day_filter'] = $DateFilter->sixtyDays();
        $data['ninety_day_filter'] = $DateFilter->ninetyDays();

        return response()->json($data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTrueDashboardDates() {

        $data['today_date_filter'] = DateFilter::today();
        $data['yesterday_date_filter'] = DateFilter::yesterday();
        $data['month_to_date_date_filter'] = DateFilter::monthToDate();
        $data['last_month_date_filter'] = DateFilter::lastMonth();
        $data['last_week_date_filter'] = DateFilter::lastWeek();
        $data['thirty_days_date_filter'] = DateFilter::thirtyDaysAgo();

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getTrueDashboard(Request $request) {
        $title = 'True Dashboard';

        $CampaignBreakages = new CampaignBreakage();

        $rev_code_submitted = $request->get('rev_code');

        $yesterday = date(DateFilter::getFormat(), time() - 60 * 60 * 24);
        $week_ago = date(DateFilter::getFormat(), time() - 60 * 60 * 24 * 7);
        $today_date_filter = DateFilter::today();
        $month_to_date_date_filter = DateFilter::monthToDate();
        $last_month_date_filter = DateFilter::lastMonth();
        $dateYesterday = DateFilter::yesterday();
        $last_week_date_filter = DateFilter::lastWeek();
        $thirty_days_filter = DateFilter::thirtyDaysAgo();
        $yesterday_date_filter = new DateFilter(['from' => $yesterday, 'to' => $yesterday]);
        $week_ago_date_filter = new DateFilter(['from' => $week_ago, 'to' => $week_ago]);


        $dates['from'] = $request->get('from') != null ? $request->get('from') : $week_ago_date_filter->from;
        $dates['to'] = $request->get('to') != null ? $request->get('to') : $dateYesterday->to;

        $rev_code = $rev_code_submitted == null ? 'all' : $rev_code_submitted;

        // get the breakage from the database
        $breakages = $CampaignBreakages->getBreakages($rev_code, $dates);
        $breakageSummationByDate = $CampaignBreakages->getBreakageSummationByDate($rev_code, $dates);

        // build the array with the added selection for all campaigns
        $temp_rev_codes = $this->getRevCodes();
        $rev_codes = $this->addAllCampaigns($temp_rev_codes);

        // new DateFilter object
        $date_filter = new DateFilter($dates);

        // set the title
        $rev_code_title = strtoupper(preg_replace('/_/', ' ', $rev_code)) . " ({$rev_code})";

        $request->get('date_from');
        $request->get('date_to');

        // TrueDashBoard model
        $TrueDashboard = new TrueDashboard();
        // daily true revenue summary
        $true_summary_daily = $TrueDashboard->trueSummary($rev_code, $dates['from'], $dates['to']);
        // total true revenue summary
        $true_summary_total = $TrueDashboard->trueSummaryTotal($rev_code, $dates['from'], $dates['to']);

        // breakage by date
        $breakagesByDate = $CampaignBreakages->breakageByDate($breakages);

        $summary_total = [];

        // daily summary
        $summary_daily = $this->summary($true_summary_daily, $breakagesByDate, $rev_code);
        // total of all the dates summary
        if (!empty($breakageSummationByDate)) {
            $summary_total = $this->summaryTotal($true_summary_total, $breakageSummationByDate, $rev_code);
        }


        $true_graph_data = $this->graphData($summary_daily);

        return view('reports.truedashboard')
            ->with('from', $dates['from'])
            ->with('to', $dates['to'])
            ->with('date_filter', $date_filter)
            ->with('today_date_filter', $today_date_filter)
            ->with('yesterday_date_filter', $yesterday_date_filter)
            ->with('week_ago_date_filter', $week_ago_date_filter)
            ->with('month_to_date_date_filter', $month_to_date_date_filter)
            ->with('last_month_date_filter', $last_month_date_filter)
            ->with('last_week_date_filter', $last_week_date_filter)
            ->with('thirty_days_date_filter', $thirty_days_filter)
            ->with('rev_code', $rev_code)
            ->with('rev_code_title', $rev_code_title)
            ->with('rev_codes', $rev_codes)
            ->with('summary', $summary_daily)
            ->with('summary_total', $summary_total)
            ->with('graph_data', $true_graph_data)
            ->with('current_date_from', $dates['from'])
            ->with('current_date_to', $dates['to'])
            ->with('breakages', $breakages)
            ->with('title', $title);
    }

    /**
     * @param $trueSummary
     * @param $breakageByDate
     * @param $rev_code
     * @return mixed
     */
    private function summary($trueSummary, $breakageByDate, $rev_code) {
//        d($trueSummary);
//        d($breakageByDate);
        $summary['total']['revenue'] = 0;
        $summary['total']['redirects'] = 0;
        $summary['total']['cost'] = 0;
        $summary['total']['profit'] = 0;
        $summary['total']['cpr'] = 0;
        $summary['total']['rpr'] = 0;
        $summary['total']['roi'] = 0;
        $summary['total']['msg'] = "No data for the current selection";
        $summary['total']['row_count'] = 0;

        $rev_codes = explode('_', $rev_code);
        $adv_code = $rev_codes[0];

        $summary['total']['adv_code'] = $adv_code;
        $temp = [];
        $count = 0;

        if (count($trueSummary) > 0) {

            foreach ($trueSummary as $row) {

                $cost = $row->cost;
                $revenue = $row->revenue;
                $redirects = $row->redirects;
                $profit = $revenue - $cost;

                if (preg_match('/^(ecn2?)$/', $adv_code)) {
                    $profit *= .6;
                }

                // if the data is 0 from the portal
                if ($redirects == 0) {
                    $cpr = 0;
                    $rpr = 0;

                } else {
                    $cpr = $cost / $redirects;
                    $rpr = $revenue / $redirects;
                }

                $summary["$row->date_report"]['date_report'] = $row->date_report;
                $summary["$row->date_report"]['revenue'] = $revenue;
                $summary["$row->date_report"]['redirects'] = $redirects;
                $summary["$row->date_report"]['cost'] = $cost;
                $summary["$row->date_report"]['profit'] = $profit;
                $summary["$row->date_report"]['cpr'] = $cpr;
                $summary["$row->date_report"]['rpr'] = $rpr;
                $summary["$row->date_report"]['roi'] = ($profit / $cost) * 100;
                $summary["$row->date_report"]['breakage'] = $breakageByDate[$row->date_report]['b_rev'];
                $summary["$row->date_report"]['breakage_rd'] = $breakageByDate[$row->date_report]['b_rd'];

                if ($count >= 1) {

                    $temp['total']['revenue'] += $revenue;
                    $temp['total']['redirects'] += $redirects;
                    $temp['total']['cost'] += $cost;
                    $temp['total']['profit'] += $profit;


                } else {

                    $temp['total']['revenue'] = $revenue;
                    $temp['total']['redirects'] = $redirects;
                    $temp['total']['cost'] = $cost;
                    $temp['total']['profit'] = $profit;

                }

                $count++;
            }

            $cpr = ($temp['total']['cost'] / $temp['total']['redirects']);
            $rpr = ($temp['total']['revenue'] / $temp['total']['redirects']);
            $roi = ($temp['total']['profit'] / $temp['total']['cost']) * 100;

            $summary['total']['revenue'] = $temp['total']['revenue'];
            $summary['total']['redirects'] = $temp['total']['redirects'];
            $summary['total']['cost'] = $temp['total']['cost'];
            $summary['total']['profit'] = $temp['total']['profit'];
            $summary['total']['cpr'] = $cpr;
            $summary['total']['rpr'] = $rpr;
            $summary['total']['roi'] = $roi;
            $summary['total']['msg'] = 'data';
            $summary['total']['row_count']++;
        }

        return $summary;
    }

    private function summaryTotal($trueSummary, $breakageSummationByDate) {
        $temp = [];

        $trueSummaryArray = $trueSummary->toArray()[0];
        $breakageArray = $breakageSummationByDate->toArray();

        foreach ($trueSummaryArray as $key => $value) {
            $temp[$key] = $value;
        }

        $temp['profit'] = $temp['revenue'] - $temp['cost'];
        $temp['roi'] = 100 * ($temp['profit'] / $temp['cost']);
        $temp['cpr'] = $temp['cost'] / $temp['redirects'];
        $temp['rpr'] = $temp['revenue'] / $temp['redirects'];

        foreach ($breakageArray as $index => $val) {
            $temp[$index] = $val;
        }

        return $temp;

    }

    /**
     * @return array
     */
    private function getRevCodes() {
        $RevenueCodes = new RevenueCodes();
        return $RevenueCodes->getRevCodes();
    }

    /**
     * @param $rows
     * @return array
     */
    private function graphData($rows) {
        $temp = [];

        foreach ($rows as $index => $row) {
            if ($index != 'total') {

                $js_timestamp = DateFilter::phpToJS($row['date_report']);
                $temp['clicks'][] = [$js_timestamp, $row['redirects']];
                $temp['revenue'][] = [$js_timestamp, $row['revenue']];
                $temp['cost'][] = [$js_timestamp, $row['cost']];
                $temp['profit'][] = [$js_timestamp, $row['profit']];
                $temp['breakage'][] = [$js_timestamp, $row['breakage']];

            }
        }

        return $temp;
    }

    /**
     * @param $codes
     * @return mixed
     */
    private function addAllCampaigns($codes) {
        $temp['all'] = 'All Campaigns';

        foreach ($codes as $code) {
            $temp[$code] = $code;
        }

        return $temp;

    }

    /**
     * top merchants
     * @param Request $request
     * @return mixed
     */
    public function getTopMerchants(Request $request) {

        $filter = new DateFilter($request->all());
        $today = DateFilter::today();
        $start_date = $today->to;
        $end_date = $today->to;
        $current_date = $today->to;
        $sort = 'requests';
        if ($request) {
            $today->to = $filter['from'];
            $start_date = $filter['from'];
            $end_date = $filter['to'];
        }

        $today_date_filter = $today;
        $yesterday_date_filter = DateFilter::yesterday();
        $month_to_date_date_filter = DateFilter::monthToDate();
        $last_month_date_filter = DateFilter::lastMonth();
        $last_week_date_filter = DateFilter::lastWeek();
        $thirty_days_filter = DateFilter::thirtyDaysAgo();

        $merchants_totals = [];
        $merchants_totals_targeted = [];
        $merchants_totals_ron = [];
        $LogAuctionsNinety = new LogAuctionsNinety();
        $CommerceAccounts = new CommerceAccounts();
        $input = $request->except('token');
        $start_date = $input['start_date'] ?? date('Y-m-d', strtotime('-1 day'));
        $end_date = $input['end_date'] ?? date('Y-m-d', strtotime('-1 day'));
        $adv_key = $input['adv_key'] ?? 'all';
        $limit = $input['limit'] ?? 10;

        $adv_code = 'all';
        $campaign_code = 'all';

        $adv_keys = $CommerceAccounts->getAdvKeys();

        if (count($input) > 0) {
            $sort = $input['sort'];
            if ($adv_key != 'all') {
                list($adv_code, $campaign_code) = explode('_', $adv_key);
            }

            $merchants_totals_targeted = $LogAuctionsNinety->topMerchants($start_date, $end_date, $sort, $limit, $adv_code, $campaign_code);
            $merchants_totals_ron = $LogAuctionsNinety->topMerchantsRon($start_date, $end_date, $sort, $limit, $adv_code, $campaign_code);
            $merchants_totals = $LogAuctionsNinety->totalTopMerchants($start_date, $end_date, $limit, $adv_code, $campaign_code, $sort);

        }

        return view('merchants.index')
            ->with('title', 'Merchant Performance')
            ->with('report_type', 'campaign')
            ->with('adv_keys', $adv_keys)
            ->with('adv_key', $adv_key)
            ->with('start_date', $start_date)
            ->with('end_date', $end_date)
            ->with('sort', $sort)
            ->with('today_date_filter', $today_date_filter)
            ->with('yesterday_date_filter', $yesterday_date_filter)
            ->with('month_to_date_date_filter', $month_to_date_date_filter)
            ->with('last_month_date_filter', $last_month_date_filter)
            ->with('last_week_date_filter', $last_week_date_filter)
            ->with('thirty_days_date_filter', $thirty_days_filter)
            ->with('merchant_totals_targeted', $merchants_totals_targeted)
            ->with('merchant_totals_ron', $merchants_totals_ron)
            ->with('limit', $limit)
            ->with('merchant_totals', $merchants_totals);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getMerchantsPerformance(Request $request) {

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

        $today_date_filter = $today;
        $yesterday_date_filter = DateFilter::yesterday();
        $month_to_date_date_filter = DateFilter::monthToDate();
        $last_month_date_filter = DateFilter::lastMonth();
        $last_week_date_filter = DateFilter::lastWeek();
        $thirty_days_filter = DateFilter::thirtyDaysAgo();

        $merchants_totals['totals'] = [];
        $merchants_totals['targeted'] = [];
        $merchants_totals['ron'] = [];

        $merged_totals['totals'] = [];
        $merged_totals['targeted'] = [];
        $merged_totals['ron'] = [];

        $LogAuctionsNinety = new LogAuctionsNinety();
        $CommerceAccounts = new CommerceAccounts();
        $sort = 'date_log';

        $adv_keys = $CommerceAccounts->getAdvKeys();

        $input = $request->except('token');
        $start_date = $input['start_date'] ?? date('Y-m-d', strtotime('-7 day'));
        $end_date = $input['end_date'] ?? date('Y-m-d', strtotime('-1 day'));
        $merchant_id = $input['merchant_id'] ?? 0;
        $adv_key = $input['adv_key'] ?? 'NA';
        $merchant_name = $input['merchant'] ?? '';
        $campaign_code_ron = 'All RON';
        $campaign_code = $adv_code = 'ALL';

        if ($adv_key != 'all' && $adv_key !== null && $adv_key != '' && $adv_key != 'NA') {
            list($adv_code, $campaign_code) = explode('_', $adv_key);
            $campaign_code_ron = $campaign_code . "ron";
        } elseif ($adv_key != 'NA') {
            $graph_data = '';

            return view('merchants.index')
                ->with('errors', ['please select an ADV KEY (e.g. [cnx_us])'])
                ->with('title', 'Merchant Performance')
                ->with('report_by', 'merchant')
                ->with('start_date', $start_date)
                ->with('end_date', $end_date)
                ->with('adv_keys', $adv_keys)
                ->with('merchant_totals', $merchants_totals['totals'])
                ->with('merchant_totals_targeted', $merchants_totals['targeted'])
                ->with('merchant_totals_ron', $merchants_totals['ron'])
                ->with('graph_data', $graph_data)
                ->with('report_type', 'merchant')
                ->with('merchant_id', $merchant_id)
                ->with('adv_key', $adv_key)
                ->with('adv_code', $adv_code)
                ->with('campaign_code', $campaign_code)
                ->with('campaign_code_ron', $campaign_code_ron)
                ->with('today_date_filter', $today_date_filter)
                ->with('yesterday_date_filter', $yesterday_date_filter)
                ->with('month_to_date_date_filter', $month_to_date_date_filter)
                ->with('last_month_date_filter', $last_month_date_filter)
                ->with('last_week_date_filter', $last_week_date_filter)
                ->with('thirty_days_date_filter', $thirty_days_filter)
                ->with('start_date', $start_date)
                ->with('end_date', $end_date)
                ->with('current_date', $current_date)
                ->with('merchant_name', $merchant_name);
        }

        /**
         * if the merchant is selected via merchant_id
         * the format is: <id> (<merchant name>) (<adv_key>)
         *
         * However, if the merchant is selected via
         * merchant_name, the format is: <id>
         **/
        if (preg_match('/^([0-9]+\s+?)\([a-zA-Z\.]+\)\s+?\([a-zA-Z\_]+\)/', $merchant_id)) {
            list($merchant_id_split, $merchant_name_split, $adv_code_split) = explode(" ", $merchant_id);
            $merchant_id = $merchant_id_split;
            $merchant_name = rtrim($merchant_name_split, ')');
            $merchant_name = ltrim($merchant_name, '(');
        }

        if (preg_match('/(^[a-zA-Z\.]+\s+?)\([0-9]+\)\s+?\([a-zA-Z\_]+\)/', $merchant_name)) {
            list($merchant_name, $code_split, $adv) = explode('(', $merchant_name);
            $merchant_id = rtrim(str_replace(')', '', $code_split));
        }

        // if the user has submitted the form to check the merchant
        $box_totals = [
            'targeted' => [],
            'ron' => [],
            'totals' => []
        ];
        if ($input) {
//            $sort = $input['sort'];
            $merchants_totals = $LogAuctionsNinety->topMerchantsByID($start_date, $end_date, $merchant_id, $adv_key, $sort);
            $true_revenue = $LogAuctionsNinety->trueRevenueData($adv_key, $start_date, $end_date, $merchant_id);
            $merged_totals = $this->mergeMerchantPerformance($merchants_totals, $true_revenue);
        }

        if (!empty($merged_totals['totals']) && !empty($merged_totals['targeted']) && !empty($merged_totals['ron'])) {
            $box_totals = $this->boxTotals($merged_totals);
        }

        $graph_data = $this->graphDataMerchants($merged_totals);

        return view('merchants.index')
            ->with('errors', [])
            ->with('title', 'Merchant Performance')
            ->with('report_by', 'merchant')
            ->with('start_date', $start_date)
            ->with('end_date', $end_date)
            ->with('adv_keys', $adv_keys)
            ->with('merchant_totals', $merged_totals['totals'])
            ->with('merchant_totals_targeted', $merged_totals['targeted'])
            ->with('merchant_totals_ron', $merged_totals['ron'])
            ->with('graph_data', $graph_data)
            ->with('report_type', 'merchant')
            ->with('adv_key', $adv_key)
            ->with('adv_code', $adv_code)
            ->with('sort', $sort)
            ->with('last_week_date_filter', $last_week_date_filter)
            ->with('thirty_days_date_filter', $thirty_days_filter)
            ->with('campaign_code', $campaign_code)
            ->with('campaign_code_ron', $campaign_code_ron)
            ->with('today_date_filter', $today_date_filter)
            ->with('yesterday_date_filter', $yesterday_date_filter)
            ->with('month_to_date_date_filter', $month_to_date_date_filter)
            ->with('last_month_date_filter', $last_month_date_filter)
            ->with('start_date', $start_date)
            ->with('end_date', $end_date)
            ->with('$current_date', $current_date)
            ->with('merchant_name', $merchant_name)
            ->with('box_totals', $box_totals)
            ->with('merchant_id', $merchant_id);

    }

    /**
     * @param $merchants
     * @return array
     */
    private function graphDataMerchants($merchants) {

        $total_collection = collect($merchants['totals']);
        $targeted_collection = collect($merchants['targeted']);
        $ron_collection = collect($merchants['ron']);

        $total_sorted = $total_collection->sortBy('date_log');
        $targeted_sorted = $targeted_collection->sortBy('date_log');
        $ron_sorted = $ron_collection->sortBy('date_log');

        $merchants['totals'] = $total_sorted;
        $merchants['targeted'] = $targeted_sorted;
        $merchants['ron'] = $ron_sorted;

        $temp = [];

        foreach ($merchants as $index => $merchant) {

            $temp[$index] = [];

            foreach ($merchant as $row) {

                $raw_revenue = $row['revenue'];
                $raw_cost = $row['cost'];

                $true_rev = $row['true_rev'];

                $profit = $raw_revenue - $raw_cost;


                $js_timestamp = DateFilter::phpToJS($row['date_log']);

                $temp[$index]['redirects'][] = [$js_timestamp, $row['redirects'], 'revenue'];
                $temp[$index]['requests'][] = [$js_timestamp, $row['requests']];
                $temp[$index]['cost'][] = [$js_timestamp, $raw_cost];
                $temp[$index]['revenue'][] = [$js_timestamp, $raw_revenue];
                $temp[$index]['profit'][] = [$js_timestamp, $profit];
                $temp[$index]['true_rev'][] = [$js_timestamp, $true_rev];

                if ($true_rev !== '-') {
                    $true_profit = $true_rev - $raw_cost;
                    $temp[$index]['true_profit'][] = [$js_timestamp, $true_profit];
                }

            }
        }

        return $temp;

    }

    /**
     * @param $merchants
     * @return array;
     */
    private function boxTotals($merchants) {
        $temp = [
            'targeted' => [],
            'ron' => [],
            'totals' => []
        ];
//        dd($merchants);
        foreach ($merchants as $type => $data) {

            foreach ($data as $row) {

                if (isset($temp[$type]['cost'])) {
                    $temp[$type]['cost'] += $row['cost'];
                } else {
                    $temp[$type]['cost'] = $row['cost'];
                }

                if (isset($temp[$type]['auctions'])) {
                    $temp[$type]['auctions'] += $row['requests'];
                } else {
                    $temp[$type]['auctions'] = $row['requests'];
                }

                if (isset($temp[$type]['redirects'])) {
                    $temp[$type]['redirects'] += $row['redirects'];
                } else {
                    $temp[$type]['redirects'] = $row['redirects'];
                }

                if (isset($temp[$type]['revenue'])) {
                    $temp[$type]['revenue'] += $row['revenue'];
                } else {
                    $temp[$type]['revenue'] = $row['revenue'];
                }

                if (isset($temp[$type]['profit'])) {
                    $temp[$type]['profit'] += $row['revenue'] - $row['cost'];
                } else {
                    $temp[$type]['profit'] = $row['revenue'] - $row['cost'];
                }

                /**
                 * True Rev Section
                 */
                if (isset($temp[$type]['true_rev'])) {
                    if ($row['true_rev'] !== '-') {
                        $temp[$type]['true_rev'] += $row['true_rev'];
                    } else {
                        $temp[$type]['true_rev'] += 0;
                    }
                } else {
                    if ($row['true_rev'] !== '-') {
                        $temp[$type]['true_rev'] = $row['true_rev'];
                    } else {
                        $temp[$type]['true_rev'] = 0;
                    }
                }

                if (isset($temp[$type]['true_profit'])) {
                    if ($row['true_rev'] !== '-') {
                        $temp[$type]['true_profit'] += $row['true_rev'] - $row['cost'];
                    } else {
                        $temp[$type]['true_profit'] += 0 - $row['cost'];
                    }
                } else {
                    if ($row['true_rev'] !== '-') {
                        $temp[$type]['true_profit'] = $row['true_rev'] - $row['cost'];
                    } else {
                        $temp[$type]['true_profit'] = 0 - $row['cost'];
                    }

                }

                if (isset($temp[$type]['sum_cnv'])) {
                    if ($row['sum_cnv'] !== '-') {
                        $temp[$type]['sum_cnv'] += $row['sum_cnv'];
                    } else {
                        $temp[$type]['sum_cnv'] += 0;
                    }

                } else {
                    if ($row['sum_cnv'] !== '-') {
                        $temp[$type]['sum_cnv'] = $row['sum_cnv'];
                    } else {
                        $temp[$type]['sum_cnv'] = 0;
                    }
                }

            }
            if (!empty($merchants[$type])) {

                $temp[$type]['roi'] = 0;
                if ($temp[$type]['cost'] > 0) {
                    // raw roi
                    $temp[$type]['roi'] = ($temp[$type]['profit'] / $temp[$type]['cost']) * 100;

                    // true roi
                    $temp[$type]['true_roi'] = ($temp[$type]['true_profit'] / $temp[$type]['cost']) * 100;
                }

                $temp[$type]['cpr'] = 0;
                $temp[$type]['rpr'] = 0;
                $temp[$type]['true_rpr'] = 0;
                if ($temp[$type]['redirects'] > 0) {
                    // raw cpr
                    $temp[$type]['cpr'] = $temp[$type]['cost'] / $temp[$type]['redirects'];
                    // raw rpr

                    $temp[$type]['rpr'] = $temp[$type]['revenue'] / $temp[$type]['redirects'];

                    // true rpr
                    $temp[$type]['true_rpr'] = $temp[$type]['true_rev'] / $temp[$type]['redirects'];
                }


            }
        }

        return $temp;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportMerchants(Request $request) {

        $Merchants = new Merchants();
        $query = $request->get('query');
        $adv_key = $request->get('adv_key') ?? 'all';

        $merchants = $Merchants->getMerchants($query, $adv_key, 1);

        return response()->json($merchants);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMerchantIds(Request $request) {

        $query = $request->get('q');
        $adv_key = $request->get('adv_key') ?? 'all';
        $Merchants = new Merchants();

        $merchants = $Merchants->getMerchantsByInt($query, $adv_key);
        return response()->json($merchants);

    }

    public function getMerchantRPPCArchives(Request $request) {

        $filter = new DateFilter($request->all());
        $today = DateFilter::today();
        $start_date = date('Y-m-01', time());
        $end_date = date('Y-m-d', time());
        $current_date = $today->to;

        if ($request) {
            $today->to = $filter['from'];
            $start_date = $filter['from'];
            $end_date = $filter['to'];
        }

        $today_date_filter = $today;
        $yesterday_date_filter = DateFilter::yesterday();
        $month_to_date_date_filter = DateFilter::monthToDate();
        $last_month_date_filter = DateFilter::lastMonth();
        $last_week_date_filter = DateFilter::lastWeek();
        $thirty_days_filter = DateFilter::thirtyDaysAgo();

        $input = $request->except('token');
        $start_date = $input['start_date'] ?? date('Y-m-01', time());
        $end_date = $input['end_date'] ?? date('Y-m-d', time());


        $CommerceAccounts = new CommerceAccounts();
        $adv_keys = $CommerceAccounts->getAdvKeys();

        $adv_key = $request->get('adv_key');
        $merchant_id = $input['merchant_id'] ?? 0;
        $merchant_name = $input['merchant'] ?? '';

        /**
         * if the merchant is selected via merchant_id
         * the format is: <id> (<merchant name>) (<adv_key>)
         *
         * However, if the merchant is selected via
         * merchant_name, the format is: <id>
         **/
        if (preg_match('/^([0-9]+\s+?)\([a-zA-Z\.]+\)\s+?\([a-zA-Z\_]+\)/', $merchant_id)) {
            list($merchant_id_split, $merchant_name_split, $adv_code_split) = explode(" ", $merchant_id);
            $merchant_id = $merchant_id_split;
            $merchant_name = rtrim($merchant_name_split, ')');
            $merchant_name = ltrim($merchant_name, '(');
        }

        if (preg_match('/(^[a-zA-Z\.]+\s+?)\([0-9]+\)\s+?\([a-zA-Z\_]+\)/', $merchant_name)) {
            list($merchant_name, $code_split, $adv) = explode('(', $merchant_name);
            $merchant_id = rtrim(str_replace(')', '', $code_split));
        }

        $merchant_rpc_archive = [];

        // if the user has submitted the form to check the merchant
        if ($input) {
            $MRA = new MerchantRPCArchive();
            $merchant_rpc_archive = $MRA->getRPCArchiveData($adv_key, $merchant_id, $start_date, $end_date);
        }

        $graph_data = [];
        $graph_data = $this->graphMRAData($merchant_rpc_archive);

        return view('merchants.merch_rpc_archive')
            ->with('title', 'Merchant Performance')
            ->with('merchant_id', $merchant_id)
            ->with('merchant_name', $merchant_name)
            ->with('merchant_rpc_archive', $merchant_rpc_archive)
            ->with('graph_data', $graph_data)
            ->with('adv_keys', $adv_keys)
            ->with('adv_key', $adv_key)
            ->with('start_date', $start_date)
            ->with('end_date', $end_date)
            ->with('last_week_date_filter', $last_week_date_filter)
            ->with('thirty_days_date_filter', $thirty_days_filter)
            ->with('today_date_filter', $today_date_filter)
            ->with('yesterday_date_filter', $yesterday_date_filter)
            ->with('month_to_date_date_filter', $month_to_date_date_filter)
            ->with('last_month_date_filter', $last_month_date_filter);

    }

    public function graphMRAData($mras) {

        $tempMRA = [];

        foreach ($mras as $index => $mra) {

            $js_timestamp = DateFilter::phpToJS($mra->date_report);

            $tempMRA['max_rpcs'][] = [$js_timestamp, $mra->rpc_max];
            $tempMRA['median_rpcs'][] = [$js_timestamp, $mra->rpc_median];
        }

        return $tempMRA;
    }

    /**
     * Domain Breakdown by Merchant
     */

    public function getMerchantBreakDown(Request $request) {
        $filter = new DateFilter($request->all());
        $today = DateFilter::today();
        $start_date = date('Y-m-01', time());
        $end_date = date('Y-m-d', time());
        $current_date = $today->to;

        if ($request) {
            $today->to = $filter['from'];
            $start_date = $filter['from'];
            $end_date = $filter['to'];
        }

        $today_date_filter = $today;
        $yesterday_date_filter = DateFilter::yesterday();
        $month_to_date_date_filter = DateFilter::monthToDate();
        $last_month_date_filter = DateFilter::lastMonth();
        $last_week_date_filter = DateFilter::lastWeek();
        $thirty_days_filter = DateFilter::thirtyDaysAgo();

        $input = $request->except('token');
        $start_date = $input['start_date'] ?? date('Y-m-01', time());
        $end_date = $input['end_date'] ?? date('Y-m-d', time());
        $limit = $input['limit'] ?? 10;
        $sort = $input['sort'] ?? 'auc';
        $display = $input['display'] ?? 'log_rev';
        $desc = $input['sort'] ?? 'auc';

        $CommerceAccounts = new CommerceAccounts();
        $adv_keys = $CommerceAccounts->getAdvKeys();

        $adv_key = $request->get('adv_key');
        $merchant_id = $input['merchant_id'] ?? 0;
        $merchant_name = $input['merchant'] ?? '';

        /**
         * if the merchant is selected via merchant_id
         * the format is: <id> (<merchant name>) (<adv_key>)
         *
         * However, if the merchant is selected via
         * merchant_name, the format is: <id>
         **/
        if (preg_match('/^([0-9]+\s+?)\([a-zA-Z\.]+\)\s+?\([a-zA-Z\_]+\)/', $merchant_id)) {
            list($merchant_id_split, $merchant_name_split, $adv_code_split) = explode(" ", $merchant_id);
            $merchant_id = $merchant_id_split;
            $merchant_name = rtrim($merchant_name_split, ')');
            $merchant_name = ltrim($merchant_name, '(');
        }

        if (preg_match('/(^[a-zA-Z\.]+\s+?)\([0-9]+\)\s+?\([a-zA-Z\_]+\)/', $merchant_name)) {
            list($merchant_name, $code_split, $adv) = explode('(', $merchant_name);
            $merchant_id = rtrim(str_replace(')', '', $code_split));
        }

        $merchant_breakdown = [];

        // if the user has submitted the form to check the merchant
        if (!empty($input)) {
            $MBD = new MerchantBreakdown();
            $merchant_breakdown = $MBD->getMBdData($adv_key, $merchant_id, $start_date, $end_date, $limit, $desc);
        }

        return view('merchants.merchant_breakdown')
            ->with('title', 'Merchant Performance')
            ->with('merchant_id', $merchant_id)
            ->with('merchant_name', $merchant_name)
            ->with('mbd_data', $merchant_breakdown)
            ->with('adv_keys', $adv_keys)
            ->with('adv_key', $adv_key)
            ->with('start_date', $start_date)
            ->with('end_date', $end_date)
            ->with('today_date_filter', $today_date_filter)
            ->with('yesterday_date_filter', $yesterday_date_filter)
            ->with('month_to_date_date_filter', $month_to_date_date_filter)
            ->with('last_week_date_filter', $last_week_date_filter)
            ->with('thirty_days_date_filter', $thirty_days_filter)
            ->with('last_month_date_filter', $last_month_date_filter)
            ->with('limit', $limit)
            ->with('sort', $sort)
            ->with('display', $display)
            ->with('desc', $desc);

    }

    /**
     * Merge Raw and True rev for merchant performance
     *
     * @param $merchants_totals
     * @param $true_revenue
     * @return mixed
     */
    private function mergeMerchantPerformance($merchants_totals, $true_revenue) {

        foreach ($merchants_totals as $index => $merchants_total_row) {

            foreach ($merchants_totals[$index] as $date_row => $row) {

                if (isset($true_revenue[$index][$date_row])) {
                    foreach ($true_revenue[$index][$date_row] as $tr_index => $tr_value) {
                        $merchants_totals[$index][$date_row][$tr_index] = $tr_value;
                    }
                } else {
                    $merchants_totals[$index][$date_row]['campaign_code'] = '-';
                    $merchants_totals[$index][$date_row]['date_report'] = '-';
                    $merchants_totals[$index][$date_row]['true_rev'] = '-';
                    $merchants_totals[$index][$date_row]['sum_cnv'] = '-';
                    /*$merchants_totals[$index][$date_row]['campaign_code'] = 0;
                    $merchants_totals[$index][$date_row]['date_report'] = 0;
                    $merchants_totals[$index][$date_row]['true_rev'] = 0;
                    $merchants_totals[$index][$date_row]['sum_cnv'] = 0;*/
                }

            }
        }

        return $merchants_totals;
    }

}
