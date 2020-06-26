<?php namespace App\Http\Controllers;

use App\Helpers\Avatars;
use App\Models\Alert;
use Illuminate\Http\Request;

class BaseController extends Controller {

    private $request;

    public function __construct(Request $Request) {
        setlocale(LC_MONETARY, 'en_US');

        $this->request = $Request;

        $importantAlerts = Alert::important()->get();
        $avatarHelper = new Avatars();

        view()->composer('layouts.sidebar', function($view) use ($importantAlerts) {
            $view->with('important_alerts', $importantAlerts);
        });
        view()->composer('layouts.navbar', function($view) use ($importantAlerts, $avatarHelper) {
            $view->with('important_alerts', $importantAlerts)->with('avatarHelper', $avatarHelper);
        });
    }

    public function render($view, $data = array()) {

        if (isset($this->request)) {
            $request = $this->request;
            $ip = $request->ip();
            $ua = $request->server('HTTP_USER_AGENT');
            $data['ip'] = $ip;
            $data['ua'] = $ua;
        }

        return view($view, $data);
    }
}