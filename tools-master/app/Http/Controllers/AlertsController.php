<?php
namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

class AlertsController extends BaseController {

    public function getIndex(Request $request) {
        $data['title'] = 'Recent System Alerts';

        //Try to parse result per page value
        $rpp = intval($request->get('rpp'));
        //Fallback to default
        if ( $rpp < 1 || $rpp > 100 ) {
            $rpp = 100;
        }

        $alerts = Alert::descendant()->paginate($rpp);

        //If not default, add rpp to pagination links
        if ( $rpp != 100 ) {
            $alerts = $alerts->appends(['rpp' => $rpp]);
        }

        $data['alerts'] = $alerts;
        $data['links'] = $alerts->links();
        $data['important_alerts'] = Alert::important()->get();

        return view('alerts.index', $data);
    }

    public function getShow( $alert_id ) {
        $Alert = Alert::find($alert_id);
        $data['title'] = $Alert->subject;
        $data['alert'] = $Alert;

        // if alerts table was last page, get page number
        if (preg_match('/alerts\?page=(\d+)$/', url()->previous(), $matches)) {
            $back_page_num = $matches[1];
            $data['back_page_num'] = $back_page_num;
            $data['back_url'] = action('AlertsController@getIndex', ['page' => $back_page_num]);
        } else {
            $data['back_url'] = action('AlertsController@getIndex');
        }


        return view('alerts.show', $data);
    }

    public function postChangeStatus(Request $request, $alert_id) {
        $Alert = Alert::find($alert_id);
        $alert_status = $request->get('status');
        $Alert->status = $alert_status;

        if( $Alert->save() ) {
            return redirect()->to($request->get('back_url'))
                ->with('message', "Status changed to [" . strtoupper($Alert->status) . "].");
        } else {
            return redirect()->action('AlertsController@getShow', $Alert->alert_id)
                ->with('message', ["type" => "error", "message" => "Error with status change: ". join(', ', $Alert->errors()->all()) . "."]);
        }
    }

}
