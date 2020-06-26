<?php
namespace App\Http\Controllers;

use App\Models\CNXDomain;

class CNXDomainController extends BaseController
{

    public function getIndex()
    {
        $data['ui']['title'] = 'CNX Domains';
        $data['campaigns'] = CNXDomain::getCNXCampaignList();
        $data['cnx_domains'] = array();
        $data['selected_campaign'] = Input::get('campaign');
        $data['selected_id'] = Input::get('merchant_id');

        if (Input::has('campaign')) {
            $params['merchant_id'] = Input::get('merchant_id');
            list($params['adv_code'], $params['campaign_code']) = explode('_', Input::get('campaign'));

            return Redirect::action('CNXDomainController@getIndex', $params);
        }
        elseif (Input::has('adv_code')) {
            $data['selected_campaign'] = Input::get('adv_code') . '_' . Input::get('campaign_code');
            $data['selected_id'] = Input::get('merchant_id');

            if (Input::get('merchant_id') === '') {
                return Redirect::action('CNXDomainController@getIndex')
                    ->with('error', 'The merchant ID field should always contain a valid value.');
            }
            else {
                $data['cnx_domains'] = CNXDomain::where('merchant_id', Input::get('merchant_id'))
                    ->where('adv_code', Input::get('adv_code'))
                    ->where('campaign_code', Input::get('campaign_code'))
                    ->get();
            }
        }

        return View::make('cnx.domains.index', $data);
    }

    public function postIndex()
    {
        $validator = Validator::make(Input::all(), ['domains' => 'required']);

        if ($validator->fails()) {
            return Redirect::action('CNXDomainController@getIndex')
                ->with('error', 'No new domain entered');
        }

        $domains = explode(PHP_EOL, trim(Input::get('domains')));
        list($adv_code, $campaign_code) = explode('_', Input::get('campaign'));

        foreach ($domains as $domain) {
            list($new_domain, $merchant_id) = explode('||', trim($domain));

            $cnxDomain = CNXDomain::updateOrCreate([
                'adv_code' => $adv_code,
                'campaign_code' => $campaign_code,
                'merchant_id' => $merchant_id,
                'domain' => $new_domain
            ]);

            $cnxDomain->save();
        }
        $rows = count($domains);

        return Redirect::action('CNXDomainController@getIndex')
            ->with('message', "{$rows} CNX domains added");
    }

    public function deleteIndex()
    {
        $validator = Validator::make(Input::all(), ['delete-domains' => 'required']);

        if ($validator->fails()) {
            return Redirect::action('CNXDomainController@getIndex')
                ->with('error', "No selected CNX domain for deletion");
        }

        $domains = Input::get('delete-domains');
        $first = CNXDomain::where('domain', $domains[0])->first();

        $params = [
            'merchant_id' => $first->merchant_id,
            'adv_code' => $first->adv_code,
            'campaign_code' => $first->campaign_code
        ];

        $rows = CNXDomain::whereIn('domain', $domains)->delete();

        return Redirect::action('CNXDomainController@getIndex', $params)
            ->with('message', "{$rows} domains deleted for CNX Merchant [{$params['merchant_id']}: {$params['adv_code']}_{$params['campaign_code']}]");
    }
}