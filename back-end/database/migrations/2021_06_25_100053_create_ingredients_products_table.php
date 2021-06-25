<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredient_product', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger("product_id")->unsigned();
            $table->foreign("product_id")->references("id")->on("products");

            $table->bigInteger("ingredient_id")->unsigned();
            $table->foreign("ingredient_id")->references("id")->on("ingredients");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredient_product');
    }
}
