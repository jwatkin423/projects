<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class WarehouseModel extends BaseModel {

    protected $connection = 'warehouse';
    protected $adrenalads_db;
    protected $db_env;
    protected $warehouse_db;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $this->db_env = env('APP_ENV') != ('production' && 'pr') ? 'dev' : 'pr';
        $this->adrenalads_db = env('DB_DATABASE', 'adrenalads_pr');
        // Sets for Models WITHOUT raw queries
        $this->setConnection($this->connection);

        // name of the warehouse connection
        $warehouse_connection = $this->connection;

        // Sets for Models WITH raw queries
        $this->warehouse_db = config('database.connections.' . $warehouse_connection . '.database');

    }

    public function getAdrenaladsConnection() {
        return $this->adrenalads_db;
    }

}
