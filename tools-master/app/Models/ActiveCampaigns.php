<?php

namespace App\Models;

use DB;

class ActiveCampaigns extends WarehouseModel {

    public function getActiveCampaigns($date) {

        $warehouse_db = $this->warehouse_db;
        $adrenalads_db = $this->adrenalads_db;

        /** @var $sql string */
        $sql = "
            SELECT
              IF(date_log, date_log, '$date') as date_log,
              a.adv_code as adv_code,
              a.campaign_code as campaign_code,
              a.status,
              auctions as requests,
              redirects
            FROM {$adrenalads_db}.campaigns a
              LEFT JOIN (
                          SELECT
                            date_log,
                            adv_code,
                            campaign_code,
                            auctions,
                            sum(redirects) as redirects
                          FROM {$warehouse_db}.agg_auctions_date
                          WHERE
                            date_log = '$date'
                            OR date_log IS NULL
                          GROUP BY
                            adv_code,
                            campaign_code) b ON a.adv_code = b.adv_code
                            AND a.campaign_code = b.campaign_code
            WHERE
              a.campaign_code != 'phone'
              AND a.status = 'active'
            GROUP BY
              a.adv_code,
              a.campaign_code
            ORDER BY
              adv_code,
              campaign_code";

        $results = DB::select($sql);

        $campaigns_active = count($results);
        $campaigns_capped = $this->determineCapped($results);
        $campaigns_no_traffic = $this->traffic($results);
        $campaigns_running = $campaigns_active - $campaigns_capped;

        $campaigns_inactive = $campaigns_no_traffic;

        $tallies['campaigns_active'] = $campaigns_active;
        $tallies['campaigns_inactive'] = $campaigns_inactive;
        $tallies['campaigns_capped'] = $campaigns_capped ?? 0;
        $tallies['campaigns_running'] = $campaigns_running;

        return $tallies;

    }

    private function determineCapped($results) {
        $count = 0;
        $CapCounter = new CapCounter();

        foreach ($results as $row) {

            $adv_code = $row->adv_code;
            $campaign_code = $row->campaign_code;
            $campaign = "{$adv_code}_{$campaign_code}";
            $redirects = $row->redirects;
            $campaign_status = $row->status;
            $running_status = $CapCounter->determineCap($campaign, $redirects, $campaign_status);
            $capped_status = $running_status['status'];

            if ($capped_status == 'Capped' || $capped_status == 'ron-capped') {

                $count++;
            }
        }

        return $count;

    }

    private function traffic($results) {

        $count = 0;
        $list = [];

        foreach ($results as $row) {

            $adv_code = $row->adv_code;
            $campaign_code = $row->campaign_code;
            $campaign = "{$adv_code}_{$campaign_code}";

            if ($row->redirects === null && $row->requests === null) {
                $list[] = $campaign;
                $count++;
            }
        }


        return ['count' => $count, 'list' => $list];

    }

}