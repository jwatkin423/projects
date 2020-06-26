<?php namespace App\Http\Composers;

use Illuminate\View\View;

use Request;

class LaradrenComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        $current_route_name = Request::route()->getName();
        $view->with('active_route', $current_route_name);
    }
}