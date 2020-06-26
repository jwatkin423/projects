<?php
namespace App\Http\Controllers;

use App\Models\GlobalTrafficCountryCode;
use App\Models\GlobalTrafficTld;

class GlobalSettingsController extends BaseController
{

    public function getIndex()
    {
        $data = [];
        $data['new_country_code'] = new GlobalTrafficCountryCode();
        $data['new_tld'] = new GlobalTrafficTld();
        $data['country_codes'] = GlobalTrafficCountryCode::getCountryCodes();
        $data['tlds'] = GlobalTrafficTld::getTlds();

        return View::make('global_settings.index', $data);
    }

    public function postCountryCode()
    {
        $country = new GlobalTrafficCountryCode(Input::all());

        if ($country->save()) {
            return Redirect::action('GlobalSettingsController@getIndex')
                ->with('message', "Country code [{$country['country_code']}] ({$country['country_name']}) has been added!");
        }
        else {
            View::share('errors', $country->errors());
            $data = [];
            $data['new_country_code'] = $country;
            $data['new_tld'] = new GlobalTrafficTld();
            $data['country_codes'] = GlobalTrafficCountryCode::getCountryCodes();
            $data['tlds'] = GlobalTrafficTld::getTlds();

            return View::make('global_settings.index', $data);
        }
    }

    public function postTld()
    {
        $tld = new GlobalTrafficTld(Input::all());

        if ($tld->save()) {
            return Redirect::action('GlobalSettingsController@getIndex')
                ->with('message', "Country code [{$tld['tld']}] ({$tld['tld_description']}) has been added!");
        }
        else {
            View::share('errors', $tld->errors());

            $data = [];
            $data['new_country_code'] = new GlobalTrafficCountryCode();
            $data['new_tld'] = $tld;
            $data['country_codes'] = GlobalTrafficCountryCode::getCountryCodes();
            $data['tlds'] = GlobalTrafficTld::getTlds();
            return View::make('global_settings.index', $data);
        }
    }

    public function deleteCountryCode($code)
    {
        if (GlobalTrafficCountryCode::deleteCode($code)) {
            return Redirect::action('GlobalSettingsController@getIndex')
                ->with('message', "Global country code was successfully deleted.");
        } else {
            return Redirect::action('GlobalSettingsController@getIndex')
                ->with('error', "Global country code was not failed to deleted.");
        }
    }

    public function deleteTld($tld)
    {
        if (GlobalTrafficTld::deleteTld($tld)) {
            return Redirect::action('GlobalSettingsController@getIndex')
                ->with('message', "Global TLD successfully deleted.");
        }
        else {
            return Redirect::action('GlobalSettingsController@getIndex')
                ->with('error', "Global TLD was not failed to deleted.");
        }
    }
}