<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_profile_id')->unsigned();
            $table->bigInteger('job_id')->unsigned();
            $table->enum('status', ['SUBMITTED', 'ON_REVIEW', 'ACCEPTED', 'REJECTED', 'WITHDRAW'])->default('SUBMITTED');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['user_profile_id', 'job_id']);
            $table->foreign('user_profile_id')->references('id')->on('user_profiles')->onDelete('cascade');;
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_applications');
    }
}
