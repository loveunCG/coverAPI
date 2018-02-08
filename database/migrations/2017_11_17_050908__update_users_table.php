<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
              $table->string('refferalcode')->nullable()->default(null)->after('usertype');
              $table->double('longitude')->nullable()->default(null)->after('refferalcode');
              $table->double('latitude')->nullable()->default(null)->after('longitude');
              $table->integer('isAvailable')->default(0)->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['refferalcode','longitude','latitude','isAvailable']);
        });
    }
}
