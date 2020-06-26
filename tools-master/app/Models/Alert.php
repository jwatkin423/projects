<?php
namespace App\Models;

class Alert extends BaseModel {

    protected $fillable = ['subject', 'body', 'status'];

    protected $primaryKey = 'alert_id';

    public static $rules = [
        'subject' => 'required',
        'body' => 'required',
        'status' => 'in:ignore,minor,info,warning,important,resolved'
    ];

    public function getPrintableAlertIdAttribute() {
        return "[#{$this->alert_id}]";
    }

    public function getStatusAttribute($attr) {
        return $attr ? $attr : 'info';
    }

    public function getStatusLabelAttribute() {
        return self::statusLabels()[$this->status];
    }

    public function scopeDescendant($query) {
        return $query->orderBy('alert_id', 'desc');
    }

    public function scopeRecent($query, $without_ignore = false) {
        if ( $without_ignore ) {
            $query = $query->where('status', '!=', 'ignore');
        }
        return $query->descendant()->limit(10);
    }

    public function scopeImportant($query) {
        return $query->descendant()->where('status','=','important');
    }

    public static function statusLabels() {
        return [
            "ignore" => "info",
            "minor" => "default",
            "info" => "primary",
            "warning" => "warning",
            "important" => "danger",
            "resolved" => "success"
        ];
    }



}
