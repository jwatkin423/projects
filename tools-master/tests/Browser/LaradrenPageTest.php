<?php

namespace Test\Browser;

use Carbon\Carbon;
use Tests\DuskTestCase;


class LaradrenPageTest extends DuskTestCase {

    /**
     * @group login
     * @group alerts
     * @group truedashboard
     * @group truedashboard_select

     * @group campaign_pacing
     * @group campaign_pacing_select
     * @group api_pacing
     * @group api_pacing_select
     * @group traffic
     * @group traffic_select
     * @group breakage
     * @group breakage_select

     * @group distribution
     * @group distribution_advertisers_select
     * @group distribution_partners_select

     * @group commerce_dashboards
     * @group top_merchants
     * @group top_merchants_select

     * @group merchants_performance
     * @group merchants_performance_select

     * @group trafficSettings

     * @group advertisers
     * @group tools
     * @group testapi
     * @group testphoneapi
     * @group geoiplookup
     * @group system
     * @group users
     */
    public function testLogin() {
        $this->browse(function($browser) {
            $browser->visit('/login')
                    ->type('email', 'admin@adrenalads.com')
                    ->type('pwd', 'adrenalads00')
                    ->press('Login')
                    ->assertPathIs('/');
        });
    }

    /**
     * @group alerts
     */
    public function testAlerts() {
        $this->browse(function($browser) {
            $browser->visit('/tools/alerts')
                    ->assertSee('System Alerts');
        });
    }

    /**
     * @group truedashboard
     */
    public function testTrueDashboard() {
        $this->browse(function($browser) {
            $browser->visit('/reports/truedashboard')
                    ->assertSee('Select Advertiser and Date Range');
        });
    }

    /**
     * @group truedashboard_select
     */
    public function testUpdateTrueDashboard() {

        $yesterday = date('Y-m-d', strtotime('-1 day'));

        $this->browse(function($browser) use($yesterday){
            $browser->visit('/reports/truedashboard')
                    ->assertSee('Select Advertiser and Date Range')
                    ->select('rev_code', 'cnx_us')
                    ->clickLink('Yesterday')
                    ->press('Update')
                    ->assertSee($yesterday)
                    ->assertSee('cnx_us')
                    ->assertSee('Breakage Percentage')
                    ->assertSee('Click Volume by Date');
        });
    }

    /**
     * @group campaign_pacing
     */
    public function testPacing() {
        $this->browse(function($browser) {
            $browser->visit('/reports/pacing')
                    ->assertSee('Select Advertiser and Date Range');
        });
    }

    /**
     * @group campaign_pacing_select
     */
    public function testUpdatePacing() {
        $this->browse(function($browser) {
            $browser->visit('/reports/pacing')
                    ->assertSee('Select Advertiser and Date Range')
                    ->select('campaign', 'cnx_us')
                    ->clickLink('Today, Yesterday, 1 Week Ago')
                    ->press('Update Graph')
                    ->assertSee('cnx_us');
        });
    }


    /**
     * @group api_pacing
     */
    public function testApiPacing() {
        $this->browse(function($browser) {
            $browser->visit('/reports/api_pacing')
                    ->assertSee('Select API Partner');
        });
    }

    /**
     * @group api_pacing_select
     */
    public function testUpdatePApiacing() {
        $this->browse(function($browser) {
            $browser->visit('/reports/api_pacing')
                    ->assertSee('Select API Partner')
                    ->select('api_partner', '10878')
                    ->clickLink('Today, Yesterday, 1 Week Ago')
                    ->press('Update Graph')
                    ->assertSee('Trellian (10878)');
        });
    }


    /**
     * @group traffic
     */
    public function testTraffic() {
        $this->browse(function($browser) {
            $browser->visit('/reports/traffic')
                    ->assertTitle('Adrenalads Tools: Traffic');
        });
    }

    /**
     * @group traffic_select
     */
    public function testUpdateTraffic() {
        $yesterday = Carbon::yesterday()
                           ->format('Y-m-d');

        $this->browse(function($browser) use ($yesterday) {
            $browser->visit('/reports/traffic')
                    ->assertTitle('Adrenalads Tools: Traffic')
                    ->clickLink('Yesterday')
                    ->select('advertiser', 'cnx')
                    ->select('campaign', 'cnx_us')
                    ->press('#update-filters')
                    ->press('#update-date')
                    ->assertSee('cnx_us')
                    ->assertSee('- All -')
                    ->assertSee('cnx')
                    ->assertSee('Requests and Redirects')
                    ->assertSee($yesterday);
        });
    }

    /**
     * @group breakage
     */
    public function testBreakage() {
        $this->browse(function($browser) {
            $browser->visit('/reports/breakage')
                    ->assertTitle('Adrenalads Tools: Breakage');
        });
    }

    /**
     * @group breakage_select
     */
    public function testUpdateBreakage() {

        $one_week_ago = date('Y-m-d', strtotime('-1 week'));

        $this->browse(function($browser) use($one_week_ago){
            $browser->visit('/reports/breakage')
                    ->assertTitle('Adrenalads Tools: Breakage')
                    ->clickLink('Today, 1 Week Ago')
                    ->press('Retrieve Breakage')
                    ->assertSee($one_week_ago);
        });

    }

    /**
     * @group distribution
     */
    public function testDistribution() {
        $this->browse(function($browser) {
            $browser->visit('/reports/distribution')
                    ->assertTitle('Adrenalads Tools: Breakdown');
        });
    }

    /**
     * @group distribution_advertisers_select
     */
    public function testUpdateDistributionAdvertisers() {

        $yesterday = Carbon::yesterday()
                           ->format('Y-m-d');

        $this->browse(function($browser) use($yesterday){
            $browser->visit('/reports/distribution')
                    ->assertTitle('Adrenalads Tools: Breakdown')
                    ->select('advertiser','cnx_us')
                    ->clickLink('Yesterday')
                    ->press('Generate Traffic Breakdown Charts')
                    ->assertInputValue('#from', $yesterday)
                    ->assertInputValue('#to', $yesterday);
        });

    }

    /**
     * @group distribution_partners_select
     */
    public function testUpdateDistributionPartners() {

        $yesterday = Carbon::yesterday()
                           ->format('Y-m-d');

        $this->browse(function($browser) use($yesterday){
            $browser->visit('/reports/distribution')
                    ->assertTitle('Adrenalads Tools: Breakdown')
                    ->radio('traffic', 'partner')
                    ->select('partner','10878')
                    ->clickLink('Yesterday')
                    ->press('Generate Traffic Breakdown Charts')
                    ->assertInputValue('#from', $yesterday)
                    ->assertInputValue('#to', $yesterday)
                    ->assertSee('10878 - Trellian');
        });

    }


    /**
     * @group commerce_dashboards
     * @group top_merchants
     */
    public function testTopMerchants() {
        $this->browse(function($browser) {
            $browser->visit('/reports/top_merchants')
                    ->assertTitle('Adrenalads Tools: Merchant Performance');
        });
    }

    /**
     * @group commerce_dashboards
     * @group top_merchants_select
     */
    public function testUpdateTopMerchants() {

        $this->browse(function($browser){

            $yesterday = date('Y-m-d', strtotime('-1 day'));

            $browser->visit('/reports/top_merchants')
                    ->assertTitle('Adrenalads Tools: Merchant Performance')
                    ->select('adv_key','cnx_us')
                    ->radio('#limit', '10')
                    ->keys('#start_date', ['{shift}', '{home}'], '{delete}')
                    ->type('#start_date', $yesterday)
                    ->press('Generate Top Merchants Report')
                    ->assertSee('cnx_us')
                    ->assertInputValue('#start_date', $yesterday)
                    ->assertInputValue('#end_date', $yesterday);
        });

    }

    /**
     * @group commerce_dashboards
     * @group merchants_performance
     */
    public function testMerchantsPerformance() {
        $this->browse(function($browser) {
            $browser->visit('/reports/merchant_performance')
                    ->assertTitle('Adrenalads Tools: Merchant Performance');
        });
    }

    /**
     * @group commerce_dashboards
     * @group merchants_performance_select
     */
    public function testUpdateMerchantsPerformanceName() {

        $this->browse(function($browser) {

            $yesterday = date('Y-m-d', strtotime('-1 day'));

            $browser->visit('/reports/merchant_performance')
                    ->assertTitle('Adrenalads Tools: Merchant Performance')
                    ->select('adv_key','cnx_us')
                    ->keys('#start_date', ['{shift}', '{home}'], '{delete}')
                    ->type('#start_date', $yesterday)
                    ->type('#merchant-id', '401')
                    ->type('#merchant-name', 'Walmart.com (401) (cnx_us)')
                    ->press('Generate Merchant Performance Report')
                    ->pause(20000)
                    ->assertInputValue('#merchant-name', 'Walmart.com (401) (cnx_us)')
                    ->assertInputValue('#merchant-id', '401')
                    ->assertInputValue('#start_date', $yesterday)
                    ->assertInputValue('#end_date', $yesterday);
        });

    }

    /**
     * @group commerce_dashboards
     * @group merchants_performance_select
     */
    public function testUpdateMerchantsPerformanceId() {

        $this->browse(function($browser) {

            $yesterday = date('Y-m-d', strtotime('-1 day'));

            $browser->visit('/reports/merchant_performance')
                    ->assertTitle('Adrenalads Tools: Merchant Performance')
                    ->select('adv_key','cnx_us')
                    ->keys('#start_date', ['{shift}', '{home}'], '{delete}')
                    ->type('#start_date', $yesterday)
                    ->type('#merchant-id', '401 (Walmart.com) (cnx_us)')
                    ->type('#merchant-name', 'Walmart.com')
                    ->press('Generate Merchant Performance Report')
                    ->pause(20000)
                    ->assertInputValue('#merchant-name', 'Walmart.com')
                    ->assertInputValue('#merchant-id', '401 (Walmart.com) (cnx_us)')
                    ->assertInputValue('#start_date', $yesterday)
                    ->assertInputValue('#end_date', $yesterday);
        });

    }

    /**
     * @group trafficSettings
     * @group campaigns
     */
    public function testCampaigns() {
        $this->browse(function($browser) {
            $browser->visit('/campaigns')
                    ->assertSee('Campaigns');
        });
    }

    /**
     * @group trafficSettings
     * @group advertisers
     */
    public function testAdvertisers() {
        $this->browse(function($browser) {
            $browser->visit('/advertisers')
                    ->assertSee('Advertisers');
        });
    }

    /**
     * @group tools
     * @group testapi
     */
    public function testTestAPI() {
        $this->browse(function($browser) {
            $browser->visit('/tools/test_api')
                    ->select('env', 'api.adrenalads.com')
                    ->assertSee('api.adrenalads.com (mace)')
                    ->select('env', 'qa-api.adrenalads.com')
                    ->assertSee('qa-api.adrenalads.com (kylo)')
                    ->select('env', 'dev-api.adrenalads.one')
                    ->assertSee('dev-api.adrenalads.one (yoda)')
                    ->select('env', 'dev-api.adrenalads.me')
                    ->assertSee('dev-api.adrenalads.me (quigon)')
                    ->select('env', 'dev-api.adrenalads.io')
                    ->assertSee('dev-api.adrenalads.io (poe)')
                    ->assertSee('API Parameters')
                    ->select('env', 'dev-api.adrenalads.me')
                    ->press('Just Request String');

            $url = $browser->driver->getCurrentURL();

            $browser->visit($url)
                    ->assertSee('http://dev-api.adrenalads.me/request.php?api_id=0&');


        });
    }

    /**
     * @group tools
     * @group testphoneapi
     */
    public function testTestPhoneAPI() {
        $this->browse(function($browser) {
            $browser->visit('/tools/test_phone_api')
                    ->assertSee('22533')
                    ->assertSee('auto')
                    ->assertSee('repair')
                    ->select('env', 'api.adrenalads.com')
                    ->assertSee('api.adrenalads.com (mace)')
                    ->select('env', 'qa-api.adrenalads.com')
                    ->assertSee('qa-api.adrenalads.com (kylo)')
                    ->select('env', 'dev-api.adrenalads.one')
                    ->assertSee('dev-api.adrenalads.one (yoda)')
                    ->select('env', 'dev-api.adrenalads.me')
                    ->assertSee('dev-api.adrenalads.me (quigon)')
                    ->select('env', 'dev-api.adrenalads.io')
                    ->assertSee('dev-api.adrenalads.io (poe)')
                    ->assertSee('API Parameters')
                    ->select('env', 'dev-api.adrenalads.one')
                    ->press('Just Request String');

            $url = $browser->driver->getCurrentURL();

            $browser->visit($url)
                    ->assertSee('http://dev-api.adrenalads.one/phone/dial.php?api_id=22533&');


        });
    }

    /**
     * @group tools
     * @group geoiplookup
     */
    public function testGeoIPLookUp() {
        $this->browse(function($browser) {
            $browser->visit('/tools/ip_lookup')
                    ->clear('ip')
                    ->type('ip', '12.25.175.66')
                    ->press('Lookup')
                    ->AssertSee('Burbank');
        });
    }

    /**
     * @group system
     * @group users
     */
    public function testUsers() {
        $this->browse(function($browser) {
            $browser->visit('/users')
                    ->AssertSee('admin@adrenalads.com')
                    ->AssertSee('nate@adrenalads.com')
                    ->AssertSee('munish@adrenalads.com')
                    ->AssertSee('josh@adrenalads.com')
                    ->AssertSee('joe@adrenalads.com');
        });
    }

}