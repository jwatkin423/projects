<?php
namespace App\Http\Controllers;

use App\Models\Advertiser;
use Illuminate\Http\Request;

class AdvertisersController extends BaseController {

    public function index(Request $request) {
        $data['title'] = 'Advertisers';

        // gets inactive advertisers
        $data['with_inactive'] = $with_inactive = $request->get('with_inactive');

        if ($with_inactive) {
            $advertisers = Advertiser::all();
        }
        else {
            $advertisers = Advertiser::withoutInactive()->get();
        }
        $data['advertisers'] = $advertisers;

        return view('advertisers.index', $data);
    }

    public function setStatus($advertiser, $status) {
        $advertiser->status = $status;
        if ($advertiser->forcesave()) {
            return redirect()->action('AdvertisersController@index')
                ->with('message', "Advertiser [{$advertiser->id}] successfully updated.");
        } else {
            return redirect()->action('AdvertisersController@index')
                ->with('message', "Something went wrong: ".implode(' ',$advertiser->errors()->all()));
        }
    }

    public function create(Request $request) {
        $view  = $request->ajax() ? 'advertisers.modal' : 'advertisers.edit';

        $data['title'] = 'Create New Advertiser';
        $data['Advertiser'] = new Advertiser();
        $data['action'] = 'AdvertisersController@store';

        return view($view, $data);
    }

    public function edit($advertiser) {
        $Advertiser = Advertiser::where('adv_code', '=', $advertiser)->first();
        // Removes commas from a list of email addresses
        if ($emails = explode(",", $Advertiser->adv_email)) {
            $trimmed_emails = array_map(function($v) {
                return trim($v);
            }, $emails);
            $Advertiser->adv_email = join("\n", $trimmed_emails);
        } 

        $data['title'] = 'Edit "'. $Advertiser->id . '" advertiser';
        $data['Advertiser'] = $Advertiser;
        $data['action'] = ['AdvertisersController@ipdate', $Advertiser->id];

        return view('advertisers.edit', $data);
    }

    public function store(Request $request) {
        $new_adv = $request->all();

        // comma-separate advertiser emails
        $new_adv['adv_email'] = preg_replace('/\s+/', ',', $new_adv['adv_email']);            
        
        // new advertiser is active
        $new_adv['status'] = 'active';

        $advertiser = new Advertiser($new_adv);

        if ($advertiser->save()) {
            if ($request->ajax()) {
                return response()->json(array(
                    'status' => true,
                    'adv_code' => $advertiser->adv_code,
                    'adv_name' => $advertiser->adv_name
                ));
            }
            else {
                return redirect()->action('AdvertisersController@index')
                    ->with('message', "Advertiser {$advertiser->adv_code} successfully created.");
            }
        }
        else {
            view()->share('errors', $advertiser->errors());
            if ($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'html' => $this->getNew($advertiser)->__toString()
                ]);
            }
            else {
                return $this->getNew($advertiser);
            }
        }
    }

    public function update(Request $request) {
        $adv_code = $request->get('adv_code');
        $Advertiser = Advertiser::where('adv_code', '=', $adv_code)->first();
        $Advertiser->fill($request->except('token'));
        // comma-separate advertiser emails
        $Advertiser['adv_email'] = preg_replace('/\s+/', ',', $Advertiser['adv_email']);

        if ($Advertiser->save()) {
            return redirect()->action('AdvertisersController@index')
                ->with('message', "Advertiser {$Advertiser->id} successfully updated.");
        }
        else {
            View::share('errors', $Advertiser->errors());
            return $this->getEdit($Advertiser);
        }
    }

}
