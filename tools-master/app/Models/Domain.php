<?php
namespace App\Models;

use Carbon\Carbon;

class Domain extends BaseModel{

    public $table = 'adrenalads_domains';

    protected $primaryKey = 'domain';

    public  $incrementing = false;

    protected $dates = ['date_expire'];

    function getDaysToExpireAttribute()
    {
        return Carbon::now()->diffInDays($this->date_expire);
    }
}