<?php
namespace App\Models;

class PacingDateFilter extends BaseModel {

    protected static function _today() {
        return DateFilter::today()->to;
    }

    protected static function _yesterday() {
        return DateFilter::yesterday()->to;
    }

    protected static function _lastWeek() {
        return date(self::getFormat(), mktime(0, 0, 0, date("m")  , date("d")-7, date("Y")));
    }

    public static function getFormat() {
        return DateFilter::getFormat();
    }

    public function getFirstDateAttribute($attr) {
        return $attr ? $attr : self::_today();
    }

    public function isFirstDateToday() {
        return $this->first_date == self::_today();
    }

    public function getSecondDateAttribute($attr) {
        return $attr ? $attr : self::_yesterday();
    }

    public function isSecondDateToday() {
        return $this->second_date == self::_today();
    }

    public function getThirdDateAttribute($attr) {
        return $attr ? $attr : self::_lastWeek();
    }

    public function isThirdDateToday() {
        return $this->third_date == self::_today();
    }

    public function getShowFullTableAttribute($attr) {
        return !!$attr;
    }

    public static function today() {
        return self::_today();
    }

    public static function yesterday() {
        return self::_yesterday();
    }

    public static function minusOneWeek() {
        return date(DateFilter::getFormat(), strtotime('-1 week'));
    }

    public static function minusTwoWeeks() {
        return date(DateFilter::getFormat(), strtotime('-2 week'));
    }

    public static function dayBefore() {
        return date(DateFilter::getFormat(), strtotime('-2 days'));
    }

    public static function monthPrior() {
        return date(DateFilter::getFormat(), strtotime('-1 month'));
    }



}
