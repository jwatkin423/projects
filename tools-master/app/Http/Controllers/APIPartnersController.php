<?php
namespace App\Http\Controllers;

use App\Models\ApiPartner;

class APIPartnersController extends BaseController {

    public function getIndex() {
        $data['title'] = 'API Partners';

        // gets inactive api partners
        $data['with_inactive'] = $with_inactive = Input::get('with_inactive');

        // get API partners
        if ($with_inactive) {
            $api_partners = ApiPartner::all();
        }
        else {
            $api_partners = ApiPartner::withoutInactive()->get();
        }
        $data['api_partners'] = $api_partners;

        return View::make('api_partners.index', $data);

    }

    public function getSetStatus($api_partner, $status) {
        $api_partner->status = $status;
        $dirty = $api_partner->getDirty();
        $original = $api_partner->getOriginal();
        if ($api_partner->save()) {
            return Redirect::action('APIPartnersController@getIndex')
                ->with('message', "API Partner [{$api_partner->api_name}] successfully updated.");

        } else {
            return Redirect::action('APIPartnersController@getIndex')
                ->with('message', "Something wrong: ".implode(' ',$api_partner->errors()->all()));
        }
    }

    public function getNew( $api_partner = null ) {
        if (is_null($api_partner)) {
            $api_partner = new ApiPartner();
        }
        $data['ui']['title'] = 'Create New API Partner';
        $data['api_partners'] = $api_partner;
        $data['action'] = 'APIPartnersController@postCreate';

        return View::make(Request::ajax() ? 'api_partners.modal' : 'api_partners.edit', $data);
    }

    public function getEdit($api_partner) {
        if ($emails = explode(",", $api_partner['api_partner_email'])) {
            $trimmed_emails = array_map(function($v) {
                return trim($v);
            }, $emails);
            $api_partner['api_partner_email'] = join("\n", $trimmed_emails);
        }

        $data['ui']['title'] = 'Edit "'.$api_partner->api_name.'" API Partner';
        $data['api_partners'] = $api_partner;
        $data['action'] = array('APIPartnersController@postUpdate', $api_partner->api_id);

        return View::make('api_partners.edit', $data);
    }

    public function postCreate() {
        $api_input = Input::all();
        $api_input['api_partner_email'] = preg_replace('/\s+/', ',', $api_input['api_partner_email']);
        $api_partner = new ApiPartner($api_input);
        if($api_partner->save()) {
            if(Request::ajax()) {
                return Response::json(array(
                    'status' => true,
                    'api_id' => $api_partner->api_id,
                    'api_name' => $api_partner->api_name
                ));
            }
            else {
                return Redirect::action('APIPartnersController@getIndex')
                    ->with('message', "API Partner: {$api_partner->api_name} successfully created.");
            }
        }
        else {
            View::share('errors', $api_partner->errors());
            if (Request::ajax()) {
                return Response::json(array(
                    'status' => false,
                    'html' => $this->getNew($api_partner)->__toString()
                ));
            } 
            else {
                return $this->getNew($api_partner);
            }
        }
    }

    public function postUpdate($api_partner) {
        $api_partner->fill(Input::all());
        $api_partner['api_partner_email'] = preg_replace('/\s+/', ',', $api_partner['api_partner_email']);
        if ($api_partner->updateUniques()) {
            return Redirect::action('APIPartnersController@getIndex')
                ->with('message', "API Partner: {$api_partner->id} successfully updated.");
        }
        else {
            View::share('errors', $api_partner->errors());
            return $this->getEdit($api_partner);
        }
    }

}
