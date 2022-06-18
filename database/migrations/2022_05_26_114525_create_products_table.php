<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_store');
            $table->unsignedBigInteger('id_category');
            $table->string('type');
            $table->string('title');
            $table->text('address');
            $table->text('area');
            $table->text('description');
            $table->text('suitable');
            $table->text('price');
            $table->text('price_meter');
            $table->text('discounted_price')->nullable();
            $table->timestamp('auction_deadline')->nullable();
            $table->timestamp('instalment_pay')->nullable();
            $table->timestamp('dp')->nullable();
            $table->timestamp('tenor')->nullable();
            $table->text('photo');
            $table->string('status')->default('publish');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_store')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('id_category')
                ->references('id')
                ->on('product_categories')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
