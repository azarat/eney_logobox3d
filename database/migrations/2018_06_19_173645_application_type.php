<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApplicationType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_type', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('status');
            $table->timestamps();
        });
        Schema::create('application_type_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_type_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['application_type_id','locale']);
            $table->foreign('application_type_id')->references('id')->on('application_type')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('application_type');
      Schema::dropIfExists('application_type_translations');
    }
}
