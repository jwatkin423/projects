<?php
namespace App\Http\Controllers;

use Carbon\Carbon;

class ParkingController extends  Controller
{
    function inventory()
    {
        $data = array_filter(Input::all());
        $domains = Domain::query();
        if(array_key_exists('status',$data))
        {
            $domains->where('renew_status',$data['status']);
        }
        if(array_key_exists('expiry',$data))
        {
            $date = Carbon::now()->addDays($data['expiry']);
            $domains->where('date_expire','<',$date->format('Y-m-d H:i:s'));
        }
        if(array_key_exists('search',$data))
        {
            $domains->where('domain','LIKE',"%".$data['search']."%");
        }
        if (array_key_exists('api_id', $data)) {
            $domains->where('api_id', '=', $data['api_id']);
        }

        $data['domains'] = $domains->get();
        $data['app_ids'] = ApiPartner::where('portfolio', 'internal')->lists('api_id_external', 'api_id_external');

        return View::make('parking.inventory',$data);
    }

}