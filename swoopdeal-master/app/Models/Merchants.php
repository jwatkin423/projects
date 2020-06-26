<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchants extends Model {

    protected $table = 'merchants';

    protected $primaryKey = 'merchant_id';

    public $timestamps = true;

    protected $fillable = [
        'cat_id',
        'merchant_name',
        'canocial_domain',
        'merchant_likes',
        'merchant_dislikes',
        'adv_code',
        'campaign_code'
    ];

}