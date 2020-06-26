<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model {

    protected $dates = ['date_orig', 'date_update'];

    public $timestamps = false;

    /* Timestamps workaround */
    const CREATED_AT = 'date_orig';
    const UPDATED_AT = 'date_update';

}
