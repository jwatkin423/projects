<?php namespace App\Libraries\Laradren;

use GeoIP;

class IPAddr {

    private $real_ip;

    public function __construct () {
        // request helper
        $this->real_ip = request()->ip();
    }

    public function get_real_ip () {
        return $this->real_ip;
    }

    public function find_geoip ($ip) {

        $reader = new GeoIP('/usr/local/share/GeoIP/GeoIP2-City.mmdb');

        $record = $reader->city($ip);

        return $record;
    }
}
