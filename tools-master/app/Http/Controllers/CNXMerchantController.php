<?php
namespace App\Http\Controllers;

use App\Models\CNXMerchant;
use App\Models\CNXDomain;
use App\Models\CNXMerchantTraffic;

class CNXMerchantController extends BaseController
{

    public function getIndex()
    {
        $data['ui']['title'] = 'CNX Merchants';
        $data['campaigns'] = CNXDomain::getCNXCampaignList();
        $data['cnx_merchants'] = array();
        $data['selected_campaign'] = Input::get('campaign');
        $data['selected_id'] = Input::get('merchant_id');

        if (Input::has('campaign')) {
            $params['merchant_id'] = Input::get('merchant_id');
            list($params['adv_code'], $params['campaign_code']) = explode('_', Input::get('campaign'));

            return Redirect::action('CNXMerchantController@getIndex', $params);
        }
        elseif (Input::has('adv_code')) {
            $merchant_id = Input::get('merchant_id');
            $adv_code =  Input::get('adv_code');
            $campaign_code = Input::get('campaign_code');

            $merchants = CNXMerchant::where('adv_code', $adv_code)
                ->where('campaign_code', $campaign_code);

            if ($merchant_id !== '' && !is_null($merchant_id)) {
                $merchants = $merchants->where('merchant_id', $merchant_id);
            }

            if (Input::get('merchants_traffic') === '1') {
                $merchants = $merchants->has('merchantTraffic');
            }

            $data['cnx_merchants'] = $merchants->paginate(100);
            $data['selected_campaign'] = $adv_code . '_' . $campaign_code;
            $data['selected_id'] = $merchant_id;
            View::share('page_appends', ['merchant_id' => $merchant_id , 'campaign_code' => $campaign_code, 'adv_code' => $adv_code]);
        }

        return View::make('cnx.merchants.index', $data);
    }


    public function getTraffic()
    {

        $data['ui']['title'] = 'Add CNX Merchant Settings';
        $data['campaigns'] = CNXDomain::getCNXCampaignList();
        $data['action'] = Input::get('action'); //Action if its adding or editing
        $data['selected_campaign'] = Input::get('campaign');
        $data['merchant_id'] = Input::get('merchant_id');
        $data['merchant_name'] = '';
        $data['traffic'] = new CNXMerchantTraffic();

        if (!is_null(Input::get('campaign')) && Input::get('campaign') !== '') {
            list($adv_code, $campaign_code) = explode('_', Input::get('campaign'));

            $merchant = CNXMerchant::with('merchantTraffic')
                ->where('merchant_id', Input::get('merchant_id'))
                ->where('adv_code', $adv_code)
                ->where('campaign_code', $campaign_code)
                ->first();

            $data['merchant_name'] = $merchant->merchant_name;

            if ($merchant->merchantTraffic) {
                $data['traffic'] = $merchant->merchantTraffic;
            } else {
                $data['traffic'] = $merchant->defaults;
            }
        }

        return View::make('cnx.merchants.traffic', $data);
    }

    public function postTraffic()
    {
        $validator = Validator::make(Input::all(), ['account_name' => 'required|exists:cnx_merchants,merchant_id']);

        if ($validator->fails()) {
            return Redirect::action('CNXMerchantController@getTraffic')
                ->with('error', 'Merchant Id entered does not exist in database');
        }

        list($adv_code, $campaign_code) = explode('_', Input::get('campaign'));

        $merchantTraffic = CNXMerchantTraffic::where('merchant_id', Input::get('account_name'))
            ->where('adv_code', $adv_code)
            ->where('campaign_code', $campaign_code)
            ->first();

        $merchant_id = Input::get('account_name');

        if (Input::get('action') === 'add') {

            if ($merchantTraffic) {
                return Redirect::action('CNXMerchantController@getIndex')
                    ->with('error', "Unable to add new traffic settings CNX Merchant [{$merchant_id}: {$adv_code}_{$campaign_code}] already exists!");
            }

            $merchantTraffic = new CNXMerchantTraffic([
                'merchant_id' => $merchant_id,
                'campaign_code' => $campaign_code,
                'adv_code' => $adv_code,
                'url_type' => Input::get('url_type'),
                'min_rpc_ron' => Input::get('min_rpc_ron'),
                'min_rpc_merchant' => Input::get('min_rpc_merchant'),
                'ron_redirects_max' => Input::get('ron_redirects_max'),
                'canonical_domain_override' => Input::get('canonical_domain_override')
            ]);

            $merchantTraffic->save();

            return Redirect::action('CNXMerchantController@getIndex')
                ->with('message', "Traffic settings added for CNX Merchant [{$merchant_id}: {$adv_code}_{$campaign_code}]");

        } elseif (Input::get('action') === 'edit') {
            $merchantTraffic->url_type = Input::get('url_type');
            $merchantTraffic->min_rpc_ron = Input::get('min_rpc_ron');
            $merchantTraffic->min_rpc_merchant = Input::get('min_rpc_merchant');
            $merchantTraffic->ron_redirects_max = Input::get('ron_redirects_max');
            $merchantTraffic->canonical_domain_override = Input::get('canonical_domain_override');
            $merchantTraffic->save();

            return Redirect::action('CNXMerchantController@getIndex')
                ->with('message', "Traffic settings updated for CNX Merchant [{$merchant_id}: {$adv_code}_{$campaign_code}]");
        }
    }

}
