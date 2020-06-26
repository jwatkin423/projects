<?php

class GlobalTrafficCountryCode extends BaseModel
{

    protected $fillable = array('country_code', 'country_name');

    protected $table = 'global_traffic_country_codes';

    public $incrementing = false;

    public static $rules = array(
        'country_code' => 'required|max:2',
        'country_name' => 'required|max:255'
    );

    public static function getCountryCodes()
    {
        return GlobalTrafficCountryCode::all();
    }

    public static function deleteCode($code)
    {
        return GlobalTrafficCountryCode::where('country_code', $code)->delete();
    }

}
