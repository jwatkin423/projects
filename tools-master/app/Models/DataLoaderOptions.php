<?php
namespace App\Models;

class DataLoaderOptions extends BaseModel {

    protected $fillable = array('env', 'data_sources');

    public function getEnvAttribute($attr) {
        return !array_key_exists($attr, self::getEnvironments()) ? 'pr' : $attr;
    }

    static public function getEnvironments() {
        return array(
            'pr' => 'Production DB',
            'dev' => 'Development DB'
        );
    }

    static public function getDataSources() {
        return array(
            'domain_keywords' => 'Domain Keywords',
            'domain_verticals' => 'Domain Verticals',
            'domain_graylist' => 'Domain Graylist'
        );
    }

    static public function getDataSourceName($data_source) {
        $data_sources = self::getDataSources();
        return $data_sources[$data_source];
    }

}
