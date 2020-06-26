<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriesLevelThree extends Model {

    protected $table = 'categories';

    protected $primaryKey = 'cat_id';

    public $timestamps = true;

    protected $fillable = [
        'cat_name',
        'cat_level',
        'cat_parent_1_id',
        'cat_parent_2_id',
        'cat_parent_3_id'
    ];

}