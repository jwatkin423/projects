<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffersData extends Model {

    protected $table = 'offers_data';

    protected $primaryKey = 'offer_id';

    protected $timestamps = true;

    protected $fillable = [
        'cat_id',
        'merchant_id',
        'offer_name',
        'offer_price',
        'offer_discount',
        'offer_url',
        'offer_short_desc',
        'offer_long_desc',
        'offer_expiry',
        'offer_keywords',
        'offer_img',
        'offer_likes',
        'offer_dislikes',
    ];

}