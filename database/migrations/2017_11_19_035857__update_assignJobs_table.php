<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAssignJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assignJobs', function (Blueprint $table) {
            $table->string('jobstatus')->nullable()->default(null)->after('job_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assignJobs', function (Blueprint $table) {
            $table->dropColumn(['jobstatus']);
        });

    }
}
