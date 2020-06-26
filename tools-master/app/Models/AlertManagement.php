<?php
namespace App\Models;

class AlertManagement extends BaseModel {

    protected $table = 'alerts_management';

    public $incrementing = false;

    protected $fillable = [
        'job_alert_name',
        'priority',
        'importance',
        'delivery',
        'reminder',
        'reminder_settings'
        ];


    public function getIdAttribute () {
        return $this->job_alert_name;
    }


}
