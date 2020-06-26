<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaradrenModel;

class AppController extends BaseController
{
    public function getIndex() {
        return $this->render('index');
    }

    public function getHome() {
        return $this->render('home');
    }

    public function getAbout() {
        return $this->render('about');
    }

    public function getTeam() {

        $team = LaradrenModel::all();

        return $this->render('team', ['team' => $team]);
    }
}
