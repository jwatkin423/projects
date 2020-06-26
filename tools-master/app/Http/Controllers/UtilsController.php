<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Debug;
use IPAddr;

class UtilsController extends BaseController {
    
    public function getAdrenAPITester () {
        $data = [];
        return $this->render('utils.api_tester', $data);
    }

    public function getGeoIP (Request $request) {

        $data['city'] = '';
        $data['state'] = '';
        $data['country_code'] = '';

        $ip = IPAddr::get_real_ip();
        /*if ($request->input('ip')) {
            $ip = $request->input('ip');
        }*/

        $data['ip'] = $ip;

        $record = IPAddr::find_geoip($ip);
        $data['city'] = $record->city->name;
        $data['state'] = $record->mostSpecificSubdivision->isoCode;
        $data['country_code'] = $record->country->isoCode;

        Debug::vardump($data);

        //return $this->render('utils.geoip', $data);
    }
}