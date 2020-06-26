<?php namespace App\Models;

use App\Models\BaseModel;

class LaradrenModel extends BaseModel {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    protected $primaryKey = 'user_id';
}
