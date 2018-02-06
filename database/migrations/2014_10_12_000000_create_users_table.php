<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('email');
            $table->string('phoneno');
            $table->string('nrc')->default('null');
            $table->date('dob')->default('2017-01-01');
            $table->string('address')->default('null');
            $table->string('postcode')->default('null');
            $table->string('state')->default('null');
            $table->string('country')->default('null');
            $table->string('image')->default('null');
            $table->string('password');
            $table->string('verifyToken')->nullable();
            $table->boolean('status');
            $table->string('devicename');
            $table->string('devicetoken');
            $table->string('usertype');
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
        Schema::dropIfExists('customers');
    }
}
