<?php
namespace App\Http\Controllers;

class LogController extends BaseController {

    public function getRequests() {
        $data['ui']['title'] = 'Live Requests Log';

        return View::make('logs.requests', $data);
    }

    public function getRedirects() {
        $data['ui']['title'] = 'Live Redirects Log';

        return View::make('logs.redirects', $data);
    }

}
