<?php
namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Merchants;
use App\Models\Offers;
use Illuminate\Http\Request;


class HomeController extends Controller {

    public function index() {

        $offers = Offers::paginate(12);
        /*$categories = Categories::all();
        $merchants = Merchants::all();*/

        return view('test.index')
            ->with('offers', $offers);
        /*->with('categories', $categories)
        ->with('merchants', $merchants);*/

    }

}