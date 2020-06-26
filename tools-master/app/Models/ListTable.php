<?php
namespace App\Models;

class ListTable extends BaseModel {

    protected $primaryKey = 'term';
    public $increament = false;

    static public function getListTables() {
        return array(
            "blacklist_source" => "Blacklist - Source",
            "whitelist_source" => "Whitelist - Source",
            "whitelist_feed" => "Whitelist - Feed (T2)"
        );
    }

    public static function find($id, $columns = array('*')) {
        $tables = self::getListTables();
        if( array_key_exists($id, $tables) ) {
            $obj = new self;
            $obj->id = $id;

            return $obj;
        } else {
            return null;
        }
    }

    public function setIdAttribute($value) {
        $this->table = $this->id = $value;
    }

    public function getNameAttribute() {
        return self::getListTables()[$this->id];
    }

    public function getTermsAttribute($value) {
        if($value) {
            return $value;
        } else {
            $terms = DB::table($this->id)->orderBy('term', 'asc')->get();
            return join("\n", array_map(function($e) {
                return $e->term;
            }, $terms));
        }
    }

    public function saveTerms($throw_error_for_testing = false) {
        DB::transaction(function() use($throw_error_for_testing) {
            DB::table($this->id)->truncate();

            //Get lines from terms text
            $terms = explode("\n", $this->terms);

            $trimmed_terms = array_map(function($v) {
                return trim($v);
            }, $terms);

            $unique_terms = array_unique($trimmed_terms);

            foreach($unique_terms as $term) {
                if(!$term) continue;

                if ( $throw_error_for_testing && App::environment() == 'testing') {
                    throw new Exception("Test Error");
                }

                $o = self::find($this->id);
                $o->term = $term;
                $o->save();
            }
        });
    }

}
