<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToNegotiationPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('negotiation_periods', function (Blueprint $table) {
            //
            $table->enum('type', \App\Entities\NegotiationPeriodType::getKeys())->default(\App\Entities\NegotiationPeriodType::DAY);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('negotiation_periods', function (Blueprint $table) {
            //
        });
    }
}
