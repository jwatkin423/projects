<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/redirect', 'SocialAuthGoogleController@redirect')->name('redirect');
Route::get('/callback', 'SocialAuthGoogleController@callback')->name('callback');

Route::group(['middleware' => 'auth'], function() {

    // Home group
    Route::group(['prefix' => '/'], function() {
        Route::get('/', 'HomeController@getIndex')->name('home');
        Route::get('/home', 'HomeController@getIndex')->name('home-alt');
        Route::get('/dates', 'HomeController@getDates')->name('dates');
        Route::post('/dbswitch', 'HomeController@postDBSwitch')->name('db-switch');
        Route::get('/ignore', 'HomeController@ignoreAlerts')->name('ignore-alerts');
        Route::post('/legacy_session', 'HomeController@setLegacyView')->name('legacy-view-set');
    });

    // Tools group
    Route::group(['prefix' => 'tools'], function() {
        // AlertsController
        Route::get('/alerts', 'AlertsController@getIndex')->name('alerts-index');
        Route::get('/alert/{alert_id}', 'AlertsController@getShow')->name("alerts-show");
        Route::post('/alert/{status}/change_status', 'AlertsController@postChangeStatus')->name("change-status");
        Route::get('/jobs', 'AlertsController@showJobAlertDetails')->name('alert-details');

        // ToolsController
        Route::get('/test_api', 'ToolsController@getTestApi')->name("adrenalads-api-tester");
        Route::get('/test_phone_api', 'ToolsController@getTestPhoneApi')->name("adrenalads-api-phone-tester");
        Route::get('/ip_lookup', 'ToolsController@getIpLookup')->name("geoip");;
        Route::get('/system/data_loader', 'ToolsController@getDataLoader');
        Route::post('/system/data_loader', 'ToolsController@postDataLoader');
    });

    // ReportsController
    Route::group(['prefix' => 'reports'], function() {
        Route::get('/pacing', 'ReportController@getPacing')->name('get-pacing');
        Route::get('/api_pacing', 'ReportController@getAPIPartnerPacing')->name('get-partner-pacing');
        Route::get('/dates', 'ReportController@getDates')->name('pacing-dates');
        Route::get('/traffic', 'ReportController@getTrafficDashboard')->name('pacing-traffic');
        Route::get('/distribution', 'ReportController@getTrafficDistribution')->name('pacing-traffic-breakdown');
        Route::get('/breakage', 'ReportController@getTrafficBreakage')->name('pacing-traffic-breakage');
        Route::get('/truedashboard', 'ReportController@getTrueDashboard')->name('true-dashboard');
        Route::get('/breakageDates', 'ReportController@getBreakageDates')->name('get-breakage-dates');
        Route::get('/trueDashboardDates', 'ReportController@getTrueDashboardDates')->name('get-true-dashboard-dates');
        Route::get('/top_merchants', 'ReportController@getTopMerchants')->name('get-top-merchants');
        Route::get('/merchant_performance', 'ReportController@getMerchantsPerformance')->name('get-merchants-performance');
        Route::get('/report_merchants', 'ReportController@reportMerchants')->name('get-report-merchants');
        Route::get('/merchants_campaign', 'ReportController@merchantsByCampaignCode')->name('get-merchants-campaign');
        Route::get('/merchant_ids', 'ReportController@getMerchantIds')->name('get-merchant-ids');
        Route::get('/merchant_rpc_archives', 'ReportController@getMerchantRPPCArchives')->name('merchant-rpc-archive');
        Route::get('/domain_breakdown', 'ReportController@getMerchantBreakDown')->name('merchant-breakdown');
    });

    // campaign routes not included with the resources
    Route::group(['prefix' => 'campaigns'], function() {
        Route::get('/', 'CampaignsController@index')->name('campaigns.index');
        Route::get('/edit/{campaign}/', 'CampaignsController@edit')->name('campaigns.edit');
        Route::get('/new', 'CampaignsController@create')->name('campaigns.create');
        Route::post('/store', 'CampaignsController@store')->name('campaigns.store');
//        Route::post('/update', 'CampaignsController@update')->name('campaigns.update');
        Route::get('/update', 'CampaignsController@update')->name('campaigns.update');
        Route::get('/status/{campaign_code?}/{status?}', 'CampaignsController@setStatus')->name('campaign-status');
    });

    // advertisers
    Route::group(['prefix' => 'advertisers'], function() {
        Route::get('/', 'AdvertisersController@index')->name('advertisers.index');
        Route::get('/new', 'AdvertisersController@create')->name('advertisers.create');
        Route::get('/{advertiser}', 'AdvertisersController@edit')->name('advertisers.edit');
        Route::post('/new', 'AdvertisersController@store')->name('advertisers.store');
        Route::post('/update/{advertiser?}', 'AdvertisersController@update')->name('advertisers.update');
        Route::get('/{advertiser?}/status/{status?}', 'AdvertisersController@setStatus')->name('advertisers-status');
    });

    // User
    Route::resource('users', 'UsersController');
});

Route::get('/debug', function () {
    $php_ver = phpversion();
    $opcache_status = opcache_get_status();
    $Debug = new Debug();
    $Debug->say("PHP version: [$php_ver]");
    $Debug->printr($opcache_status, 1);
})->name("debug");
