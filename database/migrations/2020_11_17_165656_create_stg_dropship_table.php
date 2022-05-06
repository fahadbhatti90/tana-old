<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStgDropshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stg_dropship', function (Blueprint $table) {
            $table->increments('row_id');
            $table->smallInteger('fk_vendor_id');
            $table->string('order_id', 15);
            $table->string('order_status', 20)->nullable();
            $table->string('warehouse_code', 10)->nullable();
            $table->timestamp('order_place_date')->useCurrent()->default('1999-09-09');
            $table->timestamp('required_ship_date')->useCurrent()->default('1999-09-09');
            $table->string('ship_method', 255)->nullable();
            $table->string('ship_method_code', 100)->nullable();
            $table->string('ship_to_name', 60)->nullable();
            $table->string('ship_to_address_line_1', 255)->nullable();
            $table->string('ship_to_address_line_2', 255)->nullable();
            $table->string('ship_to_address_line_3', 255)->nullable();
            $table->string('ship_to_city', 60)->nullable();
            $table->string('ship_to_state', 25)->nullable();
            $table->string('ship_to_zipcode', 15)->nullable();
            $table->string('ship_to_country', 4)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('is_it_gift', 4)->nullable();
            $table->decimal('item_cost', 14, 4)->nullable();
            $table->string('sku', 48)->nullable();
            $table->string('asin', 10);
            $table->string('item_title', 255);
            $table->unsignedSmallInteger('item_quantity')->nullable();
            $table->string('gift_message', 255)->nullable();
            $table->string('tracking_id', 255)->nullable();
            $table->timestamp('shipped_date')->useCurrent();
            $table->timestamp('capture_at')->useCurrent();
            $table->integer('date_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stg_dropship');
    }
}
