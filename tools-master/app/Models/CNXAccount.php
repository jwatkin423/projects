<?php
namespace App\Models;

class CNXAccount extends CNXModel {


    protected $fillable = array('adv_code', 'campaign_code', 'account_name', 'publisher_id', 'login', 'pwd', 'api_key', 'account_type', 'url_param_append');
    protected $table = 'cnx_accounts';
    protected $primaryKey = 'cnx_account_id';

    public static $rules = array(
        'adv_code'      => 'required',
        'campaign_code' => 'required',
        'account_name'  => 'required',
        'publisher_id'  => 'required',
        'login'         => 'required',
        'pwd'           => 'required',
        'account_type'  => 'required|in:merchant,ron,sem',
        'api_key'       => 'required|alpha_num|max:255'
    );

    public static function getAccounts() {
        return CNXAccount::all();
    }

    public static function deleteAccount($id) {
        return CNXAccount::where('cnx_account_id', $id->cnx_account_id)->delete();
    }

    public function getCampaignAttribute($attr) {
        return $this->adv_code.'_'.$this->campaign_code;
    }

}
