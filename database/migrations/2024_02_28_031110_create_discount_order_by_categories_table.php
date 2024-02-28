<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountOrderByCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_order_by_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('order_detail_id');
            $table->integer('category_discount_id');
            $table->integer('discount_percent');
            $table->bigInteger('discounted_amount');
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
        Schema::dropIfExists('discount_order_by_categories');
    }
}
