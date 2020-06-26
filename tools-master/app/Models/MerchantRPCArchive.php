<?php

namespace App\Models;

use DB;

class MerchantRPCArchive extends BaseModel {

    public function getRPCArchiveData($adv_key, $merch_id, $date_from, $date_to) {

        $table = $adv_key . '_rpc_archive';

        $mra_data = DB::table($table)
                      ->where('merchant_id', $merch_id)
                      ->where('date_report', '>=', $date_from)
                      ->where('date_report', '<=', $date_to)
                      ->get();

        return $mra_data;

    }

}
