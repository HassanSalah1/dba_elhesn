<?php

use App\Entities\Status;
use App\Entities\UserRoles;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone')->unique()->nullable();
            $table->string('password');
            $table->string('email')->unique();
            $table->string('edited_email')->nullable();
            $table->string('image')->nullable();
            $table->string('lang', 20)->default('ar');
            $table->enum('role', UserRoles::getKeys());
            $table->integer('status');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
