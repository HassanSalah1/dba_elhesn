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
            $table->increments('id');
            $table->string('name');
            $table->string('description', 1000);
            $table->integer('user_id');
            $table->integer('category_id');
            $table->integer('sub_category_id')->nullable();
            $table->integer('sub_sub_category_id')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('max_price', 8, 2)->nullable();
            $table->tinyInteger('show_user')->nullable();
            $table->tinyInteger('negotiation')->nullable();
            $table->integer('period')->nullable();
            $table->decimal('percent', 8, 2)->nullable();
            $table->enum('type', \App\Entities\ProductType::getKeys());
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
}
