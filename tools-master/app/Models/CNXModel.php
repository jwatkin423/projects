<?php
namespace App\Models;

class CNXModel extends BaseModel
{

    public static function getCNXCampaignList() {
        return [
            'cnx_us' => 'cnx_us',
            'cnx_de' => 'cnx_de',
            'cnx_fr' => 'cnx_fr',
            'cnx_gb' => 'cnx_gb',
            'cnx_ca' => 'cnx_ca',
            'nxt_us' => 'nxt_us',
        ];
    }
}