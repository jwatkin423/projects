<?php
namespace App\Models;

class TestApiRequest extends BaseModel {

    protected $fillable = ['api_id', 'search', 'ip', 'ua', 'source', 'referrer', 'min_bid', 'adv_key', 'env', 'action'];

    public function getApiIdAttribute($attr) {
        $partners = $this->getApiPartners();
        if( !array_key_exists($attr, $partners) ) {
            return array_keys($partners)[0];
        }
        return $attr;
    }

    public function getSearchAttribute($attr) {
        return $attr ? $attr : 'red shoes';
    }

    public function getIpAttribute($attr) {
        return $attr ? $attr : $this->getRealIp();
    }

    public function getUaAttribute($attr) {
        return $attr ? $attr : $_SERVER['HTTP_USER_AGENT'];
    }

    public function getSourceAttribute($attr) {
        return $attr ? $attr : 'domain.com';
    }

    public function getReferrerAttribute($attr) {
        return $attr ? $attr : 'http://www.referrer.com';
    }

    public function getActionAttribute($attr) {
        return $attr ? $attr : 'string';
    }

    public function getApiPartners() {
        $ApiPartner = new ApiPartner();
        return $ApiPartner->getApiPartnersType();
    }

    public function getEnvAttribute($attr) {
        $environments = $this->getEnvironments();
        if( !array_key_exists($attr, $environments) ) {
            return array_keys($environments)[0];
        }
        return $attr;
    }

    public function apiPartner() {
        return ApiPartner::where('api_id_external', '=', $this->api_id)->first();
    }

    public function getEnvironments() {
        $environment = [
            'dev-api.adrenalads.com' => 'dev-api.adrenalads.one (yoda/quigon)',
            'qa-api.adrenalads.com' => 'qa-api.adrenalads.com (kylo)',
            'api.adrenalads.com' => 'api.adrenalads.com (mace)'
            ];
        return $environment;
    }

    public function getRealIp() {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $ips = explode(',', $ip);
        $real_ip = $ips[0];

        return $real_ip;
    }

    public function getRequestUrl() {
        $params = array(
            "api_id" => $this->api_id,
            "api_key" => $this->apiPartner()->api_key,
            "q" => $this->ifNull($this->search),
            "ip" => $this->ip,
            "ua" => $this->ifNull($this->ua),
            "source" => $this->ifNull($this->source),
            "referrer" => $this->ifNull($this->referrer),
            "min_bid" => $this->min_bid,
            "adv_key_force" => $this->adv_key,
            "module" => "default"
        );

        // request string
        return "http://$this->env/request.php?" . http_build_query($params);

    }

    public function request($url) {
        $start_timer = microtime(true);
        $response = file_get_contents($url);
        $end_timer = microtime(true);
        $timer = ($end_timer - $start_timer) * 1000;

        $xml = simplexml_load_string($response);
        $destination_url = htmlspecialchars_decode($xml->destination_url);
        $destination_url_debug = $destination_url . "&debug=1";

        $xml_out = htmlentities($xml->asXML());

        $xml_out = preg_replace('/(\/.+?gt\;)/', '${1}<br />', $xml_out);
        $xml_out = preg_replace('/\&lt\;destination_url\&gt\;(.+?)\&lt\;\/destination_url\&gt\;/', '&lt;destination_url&gt;<a href="'.$destination_url.'" target="_blank">${1}</a>&lt;/destination_url&gt; [<a href="'.$destination_url_debug.'" target="_blank">debug</a>]', $xml_out);

        return array("timer" => $timer, "raw" => $response, "xml" => $xml_out);
    }

    protected function ifNull($attr) {
        return $attr == '[null]' ? '' : $attr;
    }

}
