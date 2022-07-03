<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('job_application_id')->unsigned();
            $table->bigInteger('user_document_id')->unsigned();

            $table->foreign('job_application_id')->references('id')->on('job_applications')->onDelete('cascade');;
            $table->foreign('user_document_id')->references('id')->on('user_documents')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_documents');
    }
}
