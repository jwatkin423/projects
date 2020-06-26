<?php

namespace App\Models;

use DB;

class MerchantBreakdown extends WarehouseModel {

    private $tempTable;
    private $tempLimit;
    public function getMBdData($adv_key, $merchant_id, $date_from, $date_to, $limit, $desc) {
        $api_id = 5439;
        $warehouse_db = $this->warehouse_db;
        list($adv_code, $campaign_code) = explode('_', $adv_key);

        $this->tempLimit = $limit;

        $this->tempTable = $adv_key . '_reports';
        DB::enableQueryLog();
        $mbd_la90_data = DB::table($warehouse_db . '.log_auctions_90')
                      ->selectRaw("
                            api_id,
                            domain,
                            CONCAT(api_id, '_', domain) AS domain_key,
                            count(*) AS auc,
                            sum( auction_win ) AS raw_rd,
                            sum(IF( auction_win = 1, revenue, NULL )) AS raw_rev,
                            avg(IF( auction_win = 1, bid, NULL )) AS avg_win_bid,
                            SUM(IF( auction_win = 1, bid, NULL )) as cost,
                            max( bid ) AS max_bid")
                      ->where('meta1_val', $merchant_id)
                      ->where('date_log', '>=', $date_from)
                      ->where('date_log', '<=', $date_to)
                      ->where('adv_code', $adv_code)
                      ->where('campaign_code', $campaign_code)
                      ->groupBy('api_id')
                      ->groupBy('domain');

        if ($this->tempLimit !== 'all') {
            $mbd_la90_data = $mbd_la90_data->limit($this->tempLimit);
        }

        $mbd_la90_data = $mbd_la90_data->orderBy($desc, 'desc')->get();

        $query = DB::getQueryLog();
//        dd(end($query));
        return $this->processRawLogResults($mbd_la90_data, $adv_code, $campaign_code, $date_to, $date_from, $this->tempLimit);

    }

    private function processRawLogResults($log_results, $adv_code, $campaign_code, $date_to, $date_from, $limit) {
        $temp = [];

        $Select = " api_id,
                    domain,
                    CONCAT(api_id, '_', domain) AS domain_key,
                    sum( redirects ) AS true_rd,
                    sum( revenue ) AS true_rev,
                    sum( cnv ) AS sum_cnv,
                    sum( cnv ) / sum( redirects ) as cnv_rate";

        $emptyCount = 0;

        foreach ($log_results as $row) {
            $tempData = [];
            $mbd_data = DB::table($this->tempTable)
                          ->selectRaw($Select)
                          ->where('date_report', '>=', $date_from)
                          ->where('date_report', '<=', $date_to)
                          ->whereRaw("CONCAT_WS('_', api_id, domain) = '{$row->domain_key}'")
                          /*->where('adv_code', $adv_code)
                          ->where('campaign_code', $campaign_code)*/
                          ->groupBy('api_id')
                          ->groupBy('domain')
                          ->get()
                          ->toArray();

            $temp_data = array_shift($mbd_data);

            if ($temp_data) {

                $temp[] = [
                    'api_id' => $row->api_id,
                    'domain' => $row->domain,
                    'auc' => $row->auc,
                    'cost' => $row->cost,
                    'raw_rd' => $row->raw_rd,
                    'raw_rev' => $row->raw_rev,
                    'avg_win_bid' => $row->avg_win_bid,
                    'max_bid' => $row->max_bid,
                    'true_rd' => $temp_data->true_rd,
                    'true_rev' => $temp_data->true_rev,
                    'sum_cnv' => $temp_data->sum_cnv,
                    'cnv_rate' => $temp_data->cnv_rate,
                ];

            } else {
                $emptyCount++;
                $temp[] = [
                    'api_id' => $row->api_id,
                    'domain' => $row->domain,
                    'auc' => $row->auc,
                    'cost' => $row->cost,
                    'raw_rd' => $row->raw_rd,
                    'raw_rev' => $row->raw_rev,
                    'avg_win_bid' => $row->avg_win_bid,
                    'max_bid' => $row->max_bid,
                    'true_rd' => "-",
                    'true_rev' => "-",
                    'sum_cnv' => "-",
                    'cnv_rate' => "-",
                ];
            }
        }

        return $temp;
    }

}
