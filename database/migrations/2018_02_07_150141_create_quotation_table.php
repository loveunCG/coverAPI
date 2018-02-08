<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_id')->unsigned()->index();
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('jod_id')->unsigned()->index();
            $table->foreign('jod_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->string('quotation_price', 100);
            $table->text('quotation_description');
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
        Schema::dropIfExists('quotations');
    }
}
