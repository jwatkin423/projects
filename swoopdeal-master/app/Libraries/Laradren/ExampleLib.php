<?php namespace App\Libraries\Laradren;

class ExampleLib {

    private $str;

    public function __construct() {
        $this->str = "Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.";
    }

    public function get_str () {
        return $this->str;
    }
}
