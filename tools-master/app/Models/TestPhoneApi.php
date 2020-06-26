<?php
namespace App\Models;

class TestPhoneApi extends TestApiRequest {

    protected $fillable = ['api_id', 'search', 'env', 'action', 'location', 'api_key', 'source', 'category'];

    public function getSearchAttribute($attr) {
        return $attr ? $attr : 'auto repair';
    }

    public function apiKey() {
        return $this->api_key = $this->getAPIKey($this->search);
    }

    public function getRequestUrl() {
        $params = [
            'api_id' => $this->api_id,
            'api_key' => $this->apiPartner()->api_key,
            'q' => $this->search,
            'source' => $this->source,
            'category' => $this->category,
            'zip' => $this->location,
        ];

        // request string
        return "http://$this->env/phone/dial.php?" . http_build_query($params);

    }

    public function getSourceAttribute($attr) {
        return $attr ? $attr : null;
    }

    public function getCategoryAttribute($attr) {
        return $attr ? $attr : null;
    }

    public function getApiPartners() {
        $ApiPartner = new ApiPartner();
        return $ApiPartner->getApiPartnersType('phone');
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

        return ["timer" => $timer, "raw" => $response, "xml" => $xml_out];
    }

    private function parseIpResponse($response) {

        $ips = [];
        $data_response = preg_split('/\n|\r\n?/', $response);

        foreach ($data_response as $index => $line) {
            if (preg_match('/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/', $line)) {
                $new_line = str_replace('	&lt;h2&gt;Your IP Address: ', '', $line);
                $ip = ltrim(str_replace('&lt;/h2&gt;', '', $new_line));

                if (!preg_match('/(74)\.(124)\.(207)\.[0-9]{1,3}/', $ip)) {
                    $ips[] = $ip;
                }
            }
        }

        return $ips;

    }

}