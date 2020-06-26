<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('merchants', function (Blueprint $table) {
            $table->increments('merchant_id');
            $table->integer('cat_id')->unsigned();
            $table->string('merchant_name', 255);
            $table->string('canonical_domain', 255);
            $table->integer('merchant_likes')->default(0);
            $table->integer('merchant_dislikes')->default(0);
            $table->string('adv_code', 32);
            $table->string('campaign_code', 32);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('merchants');
    }
}
