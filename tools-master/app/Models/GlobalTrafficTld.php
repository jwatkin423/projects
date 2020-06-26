<?php
namespace App\Models;

class GlobalTrafficTld extends BaseModel
{

    protected $fillable = array('tld', 'tld_description');

    protected $table = 'global_traffic_tlds';

    public $incrementing = false;

    public static $rules = array(
        'tld' => 'required|max:5',
        'tld_description' => 'required|max:255'
    );

    public static function getTlds()
    {
        return GlobalTrafficTld::all();
    }

    public static function deleteTld($tld)
    {
        return GlobalTrafficTld::where('tld', $tld)->delete();
    }
}
