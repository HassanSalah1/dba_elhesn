<?php

use App\Entities\OrderUserType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', \App\Entities\OrderType::getKeys());
            $table->enum('status', \App\Entities\OrderStatus::getKeys());
            $table->integer('shipment_id')->nullable();
            $table->integer('payment_method_id')->nullable();
            $table->string('reason')->nullable();
            $table->string('shipment_code', 15)->nullable();
            $table->integer('user_id');
            $table->enum('user_type', OrderUserType::getKeys());
            $table->integer('other_user_id')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
