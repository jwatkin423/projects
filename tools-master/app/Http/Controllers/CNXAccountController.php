<?php
namespace App\Http\Controllers;

use App\Models\CNXAccount;

class CNXAccountController extends BaseController {

    public function getIndex() {
        $data['ui']['title'] = 'CNX Accounts';

        $data['accounts'] = CNXAccount::getAccounts();
        return View::make('cnx.accounts.index', $data);
    }

    public function getNew($cnx = null) {
        if ( is_null($cnx) ) {
            $cnx = new CNXAccount();
        }

        $data['ui']['title'] = 'New CNX Account';
        $data['cnx'] = $cnx;
        $data['action'] = 'CNXAccountController@postCreate';

        $data['campaigns'] = CNXAccount::getCNXCampaignList();

        return View::make(Request::ajax() ? 'cnx.modal' : 'cnx.accounts.edit', $data);
    }

    public function getEdit($cnx) {

        $data['ui']['title'] = 'Edit CNX Account';
        $data['cnx'] = $cnx;
        $data['action'] = array('CNXAccountController@postUpdate', $cnx->cnx_account_id);
        $data['campaigns'] = CNXAccount::getCNXCampaignList();

        return View::make('cnx.accounts.edit', $data);
    }

    public function postCreate() {
        $new_cnx = Input::all();

        // split campaign into advertiser and campaign codes
        $campaign = $new_cnx['campaign'];
        list($adv_code, $campaign_code) = explode('_', $campaign);
        $new_cnx['adv_code'] = $adv_code;
        $new_cnx['campaign_code'] = $campaign_code;

        $cnx = new CNXAccount($new_cnx);

        if ($cnx->save()) {
            if (Request::ajax()) {
                return Response::json(array(
                    'status' => true,
                    'adv_code' => $cnx->adv_code,
                    'adv_name' => $cnx->adv_name
                ));
            }
            else {
                return Redirect::action('CNXAccountController@getIndex')
                    ->with('message', "CNX Account [{$cnx->account_name}] successfully created.");
            }
        }
        else {
            View::share('errors', $cnx->errors());
            if (Request::ajax()) {
                return Response::json(array(
                    'status' => false,
                    'html' => $this->getNew($cnx)->__toString()
                ));
            }
            else {
                return $this->getNew($cnx);
            }
        }
    }

    public function postUpdate($cnx) {
        $new_cnx = Input::all();

        // split campaign into advertiser and campaign codes
        $campaign = $new_cnx['campaign'];
        list($adv_code, $campaign_code) = explode('_', $campaign);
        $new_cnx['adv_code'] = $adv_code;
        $new_cnx['campaign_code'] = $campaign_code;

        $cnx->fill($new_cnx);

        if ($cnx->save()) {
            return Redirect::action('CNXAccountController@getIndex')
            ->with('message', "CNX [{$cnx->account_name}] successfully updated.");
        } 
        else {
            View::share('errors', $cnx->errors());
            return $this->getEdit($cnx);
        }
    }

    public function deleteAccount($id) {
        if (CNXAccount::deleteAccount($id)) {
            return Redirect::action('CNXAccountController@getIndex')
            ->with('message', "CNX Account successfully deleted.");
        } else {
            return Redirect::action('CNXAccountController@getIndex')
            ->with('message', "CNX Account did not successfully delete.");
        }
    }

}
