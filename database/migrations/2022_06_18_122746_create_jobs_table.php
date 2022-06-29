<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_profile_id')->unsigned();
            $table->string('position');
            $table->enum('type', ['INTERN', 'FULLTIME', 'PARTTIME', 'FREELANCE'])->default('FULLTIME');
            $table->string('description');
            $table->boolean('isRemote')->default(false);
            $table->string('city')->nullable(true);
            $table->string('province')->nullable(true);
            $table->string('duration')->nullable(true); // in months
            $table->bigInteger('minSalary');
            $table->bigInteger('maxSalary');
            $table->dateTime('expired');
            $table->boolean('active')->default(true);
            $table->boolean('courseRequirement')->default(false);

            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('company_profile_id')->references('id')->on('company_profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
