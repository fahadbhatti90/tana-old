<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactScSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('fact_sc_sale', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->unsignedDecimal('ordered_product_sales', 9, 2)->nullable();
            $table->unsignedMediumInteger('units_ordered')->nullable();
            $table->unsignedMediumInteger('total_ordered_items')->nullable();
            $table->unsignedDecimal('average_sales_per_order_item', 7, 2)->nullable();
            $table->unsignedDecimal('average_units_per_order_item', 7, 2)->nullable();
            $table->unsignedDecimal('average_selling_price', 7, 2)->nullable();
            $table->unsignedMediumInteger('sessions')->nullable();
            $table->decimal('order_item_session_percentage', 5, 2)->nullable();
            $table->unsignedSmallInteger('average_offer_count')->nullable();
            $table->date('sale_date')->default('1999-09-09');
            $table->unsignedInteger('date_key');
            $table->timestamp('capture_at')->useCurrent();

            $table->index(['fk_vendor_id', 'sale_date'], 'idx_vendor_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('fact_sc_sale');
    }
}
