<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('offer_id');
            $table->integer('cat_id')->unsigned();
            $table->integer('merchant_id');
            $table->string('offer_name', 255);
            $table->decimal('offer_price', 10, 5);
            $table->decimal('offer_discount', 10, 5);
            $table->string('offer_url', 255);
            $table->string('offer_short_desc', 255);
            $table->text('offer_long_desc');
            $table->dateTime('offer_expiry');
            $table->string('offer_keywords', 255);
            $table->string('offer_img', 255)->nullable();
            $table->integer('offer_likes')->default(0);
            $table->integer('offer_dislikes')->default(0);
            $table->decimal('offer_rpc', 10, 5);
            $table->string('adv_code', 32);
            $table->string('campaign_code', 32);
            $table->string('offer_source', 255);
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
        Schema::dropIfExists('offers');
    }
}
