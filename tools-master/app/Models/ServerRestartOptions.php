<?php
namespace App\Models;

class ServerRestartOptions extends BaseModel {

    protected $fillable = array('server', 'comment');

    public function getServerAttribute($attr) {
        return !array_key_exists($attr, self::getServers()) ? 'dev' : $attr;
    }

    static public function getServers() {
        return array(
            'production' => 'gates.adrenalads.com (production)',
            'development' => 'zuck.adrenalads.com (development)'
        );
    }

}
