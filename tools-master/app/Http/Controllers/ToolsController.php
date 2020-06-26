<?php
namespace App\Http\Controllers;

use App\Models\TestPhoneApi;
use App\Models\TestApiRequest;
use Illuminate\Http\Request;
use IP;

class ToolsController extends BaseController {

    public function getIpLookup(Request $request) {
        $data['title'] = "IP Lookup";
        $data['city'] = '';
        $data['state'] = '';
        $data['country_code'] = '';
        $data['longitude'] = '';
        $data['latitude'] = '';

        $ip = $request->get('ip');
        if ($ip) {
            $data['ip'] = $ip;

            $record = IP::geo_lookup($ip);
            $data['city'] = $record->city->name;
            $data['state'] = $record->mostSpecificSubdivision->isoCode;
            $data['country_code'] = $record->country->isoCode;
            $data['longitude'] = $record->location->longitude;
            $data['latitude'] = $record->location->latitude;
        }
        else {
            $data['ip'] = IP::get_real_ip();
        }


        return view('tools.ip_lookup', $data);
    }

    public function getTestApi(Request $Request) {
        $data['title'] = 'Ad Tester';

        $ApiRequest = new TestApiRequest($Request->all());
        $data['test_api_request'] = $ApiRequest;
        $data['request_url'] = $ApiRequest->getRequestUrl();

        if ( $ApiRequest->action == 'request' ) {
            $result = $ApiRequest->request($data['request_url']);
            
            $data['timer'] = sprintf("%.4f", $result['timer']);
            $data['xml_out'] = $result['xml'];
        }

        return view('tools.test_api', $data);
    }

    public function getTestPhoneApi(Request $Request) {
        $title = 'Phone API Tester';
        $timer = 0;
        $xml_out = '';

        $TestPhoneApi = new TestPhoneApi($Request->all());

        $request_url = $TestPhoneApi->getRequestUrl();

        $location = $TestPhoneApi->location;

        if ($TestPhoneApi->action == 'request') {
            $result = $TestPhoneApi->request($request_url);

            $timer = sprintf("%.4f", $result['timer']);
            $xml_out = $result['xml'];
        }


        return view('tools.test_phone_api')
            ->with('title', $title)
            ->with('timer', $timer)
            ->with('request_url', $request_url)
            ->with('xml_out', $xml_out)
            ->with('location', $location)
            ->with('test_phone_api', $TestPhoneApi);
    }

}
