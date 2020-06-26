<?php
namespace App\Models;

class RevenueCodes extends BaseModel {
    protected $table = 'revenue_codes';

    public $incrementing = false;


    protected $fillable = [
        'rev_code',
        'currency_code',
        'login',
        'pwd',
        'rev_host',
        'rev_api',
        'rev_meta1',
        'rev_meta2'
    ];

    public function getRevCodes() {
        $revCodes = RevenueCodes::all();

        $result  = [];
        foreach ($revCodes as $revCode){
            $result[$revCode->rev_code] = $revCode->rev_code;
        }

        return $result;
    }

}