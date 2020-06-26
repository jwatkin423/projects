<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller {

    private $request;

    public function __construct(Request $Request) {
        $this->request = $Request;
    }

    public function render($view, $data = array()) {

        return view($view, $data);
    }
}