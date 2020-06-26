<?php
namespace App\Models;

class Country {

    public static function all() {
        return require app_path()."/../vendor/umpirsky/country-list/data/en/country.php";
    }

}
