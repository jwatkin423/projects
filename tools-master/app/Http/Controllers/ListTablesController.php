<?php
namespace App\Http\Controllers;

class ListTablesController extends BaseController {

    public function getShow($list_table) {
        $data['ui']['title'] = $list_table->name . " [" . $list_table->id ."]";

        $data['list_table'] = $list_table;

        return View::make('list_table.show', $data);
    }

    public function postUpdateTerms($list_table) {
        $list_table->terms = Input::get('terms');

        try {
            $list_table->saveTerms(Input::get('throw_error_for_testing'));
            return Redirect::action('ListTablesController@getShow', $list_table->id)
                ->with('message', "Changes saved to ". $list_table->name ." [". $list_table->id ."].  Please restart the API server for these changes to take effect.");
        } catch(Exception $e) {
            return Redirect::action('ListTablesController@getShow', $list_table->id)
                ->with('message', array("type" => "error", "message" => "Something wrong: ".$e->getMessage()));
        }
    }
}
