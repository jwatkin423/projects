<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function(Blueprint $table){
            $table->increments('cat_id');
            $table->string('cat_name', 255);
            $table->integer('cat_level')->unsigned();
            $table->integer('cat_parent_1_id')->default(0);
            $table->integer('cat_parent_2_id')->default(0);
            $table->integer('cat_parent_3_id')->default(0);
            $table->string('cat_keywords', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
