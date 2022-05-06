<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MetadataSale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata_core_sale', function (Blueprint $table) {

            $table->increments('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->date('max_sale_date')->default('1999-09-09');
            $table->dateTime('inserted_at', 0);
            $table->index(['fk_vendor_id', 'max_sale_date'], 'idx_vendor_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metadata_core_sale');
    }
}
