<?php namespace App\Helpers;

class Debug {

    public function _quit($quit) {
        if ($quit) {
            exit(0);
        }
    }

    public function say($text, $quit = false) {
        echo("$text<br />");

        // quit?
        $this->_quit($quit);
    }

    public function vardump($var, $quit = false) {
        echo('<pre>');
        var_dump($var);
        echo('</pre>');

        // quit?
        $this->_quit($quit);
    }


    public function printr($var, $quit = false) {
        echo('<pre>');
        print_r($var);
        echo('</pre>');

        // quit?
        $this->_quit($quit);
    }
}
