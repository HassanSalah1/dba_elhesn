<?php

use App\Entities\OrderUserType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDamainOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('damain_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('category_id')->nullable();
            $table->integer('sub_category_id')->nullable();
            $table->integer('sub_sub_category_id')->nullable();
            $table->string('name')->nullable();
            $table->string('description', 1000)->nullable();
            $table->decimal('price', 16, 2)->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('address')->nullable();
            $table->string('user_name')->nullable();
            $table->string('phonecode', 10)->nullable();
            $table->string('phone')->nullable();
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
        Schema::dropIfExists('damain_orders');
    }
}
