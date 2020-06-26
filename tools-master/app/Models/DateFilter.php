<?php
namespace App\Models;

use function GuzzleHttp\Psr7\str;

class DateFilter extends BaseModel {

    protected $fillable = ['from', 'to'];

    public static function getFormat() {
        return 'Y-m-d';
    }
    public function getToAttribute($attr) {
        return $attr ? $attr : date(self::getFormat());
    }

    public function getFromAttribute($attr) {
        return $attr ? $attr : date(self::getFormat());
    }

    public function isToday() {
        return ($this->from == $this->to) && ($this->from == self::_today());
    }

    public function isYesterday() {
        return ($this->from == $this->to) && ($this->from == self::_yesterday());
    }

    protected static function _today() {
        return date(self::getFormat());
    }

    protected static function _yesterday() {
        return date(self::getFormat(), time() - 60*60*24);
    }

    public static function today() {
        $today = self::_today();
        return new self(["to" => $today, "from" => $today]);
    }

    public static function yesterday() {
        $yesterday = self::_yesterday();
        return new self(["to" => $yesterday, "from" => $yesterday]);
    }

    public static function _monthToDate() {
        $current_month = date('Y-m', time());
        $from = "$current_month-01";
        $to = date('Y-m-d', time());
        return ['to' => $to, 'from' => $from];
    }

    public static function monthToDate() {
        $dates = self::_monthToDate();
        return new self(["to" => $dates['to'], "from" => $dates['from']]);
    }

    public static function _lastMonth() {
        $from = date(self::getFormat(), strtotime('first day of previous month'));
        $to = date(self::getFormat(), strtotime('last day of previous month'));
        return ['to' => $to, 'from' => $from];
    }

    public static function lastMonth() {
        $dates = self::_lastMonth();
        return new self(["to" => $dates['to'], "from" => $dates['from']]);
    }

    public static function _last_week() {
        $from = date(self::getFormat(), strtotime('-1 week'));
        $to = self::_yesterday();

        return ['to' => $to, 'from' => $from];
    }

    public static function lastWeek() {
        $dates = self::_last_week();

        return new self (['to' => $dates['to'], 'from' => $dates['from']]);
    }

    public static function _thirty_days_ago() {
        $from = date(self::getFormat(), strtotime('-30 days'));
        $to = self::_yesterday();
        return ['to' => $to, 'from' => $from];
    }

    public static function thirtyDaysAgo() {
        $dates = self::_thirty_days_ago();

        return new self (['to' => $dates['to'], 'from' => $dates['from']]);
    }

    public function __toString() {
        if( $this->isToday() ) {
            return "Today";
        } elseif( $this->isYesterday() ) {
            return "Yesterday";
        } else {
            return $this->from." to ".$this->to;
        }
    }

    public function _thirtyDays() {
        $from = date(self::getFormat(), strtotime('-30 days'));
        $to = date(self::getFormat(), time());
        return ['from' => $from, 'to' => $to];
    }

    public function thirtyDays() {
        return $this->_thirtyDays();
    }

    public function _sixtyDays() {
        $from = date(self::getFormat(), strtotime('-60 days'));
        $to = date(self::getFormat(), time());
        return ['from' => $from, 'to' => $to];
    }

    public function sixtyDays() {
        return $this->_sixtyDays();
    }

    public function _ninetyDays() {
        $from = date(self::getFormat(), strtotime('-90 days'));
        $to = date(self::getFormat(), time());
        return ['from' => $from, 'to' => $to];
    }

    public function ninetyDays() {
        return $this->_ninetyDays();
    }

    public static function phpToJS($date) {
        return strtotime($date . " UTC") * 1000;

    }

}
