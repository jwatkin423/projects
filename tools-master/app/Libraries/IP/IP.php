<?php
namespace App\Libraries\IP;

use GeoIP;

class IP {

    public static function get_real_ip() {
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

    public static function geo_lookup($ip) {

        $reader = new GeoIP('/usr/local/share/GeoIP/GeoIP2-City.mmdb');

        $record = $reader->city($ip);

        return $record;
    }
}
