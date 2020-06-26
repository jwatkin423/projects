<?php namespace App\Helpers;

use Route;

class ViewHelper {

    function isActiveRoute ($route, $output = 'active') {
        if (Route::currentRouteName() == $route) {
            return $output;
        }
    }

}
