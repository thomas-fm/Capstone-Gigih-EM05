<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_profile_id')->unsigned();
            $table->bigInteger('course_id')->unsigned();
            $table->uuid('registration_id');
            $table->enum('status', ['TAKEN', 'COMPLETED', 'FAILED'])->default('TAKEN');
            $table->float('grade')->default(0);
            $table->float('progress')->default(0);
            $table->dateTime('due');
            $table->dateTime('expired')->nullable(true);

            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_profile_id')->references('id')->on('user_profiles');
            $table->foreign('course_id')->references('id')->on('courses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
}
