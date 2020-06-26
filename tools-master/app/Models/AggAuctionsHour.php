<?php
namespace App\Models;

use DB;
use Log;

class AggAuctionsHour extends PacingReporting {

    protected $fillable = [
        'date_log',
        'date_log_hh',
        'api_id',
        'adv_code',
        'campaign_code',
        'cost',
        'redirects',
        'revenue'
    ];

    public function __construct(array $attributes = []) {
        $this->setConnection('warehouse');

    }

    protected $table = 'agg_auctions_hour';

    public function today() {
        return date('Y-m-d');
    }

    protected $hour = 1;

    public function hour() {
        return $this->hour++ % 24;
    }

    public function randomCost() {
        return rand(10000, 30000)/100;
    }

    public function randomRequests() {
        return rand(500, 2000);
    }

    public function randomRevenue() {
        //from 110% to 160% from random cost
        return self::randomCost()*(rand(110, 160)/100);
    }

    public function randomRedirects() {
        return rand(50, 200);
    }


}
