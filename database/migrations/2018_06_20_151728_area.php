<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Area extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('area', function (Blueprint $table) {
             $table->increments('id');
             $table->boolean('status');
             $table->integer('application_type_id')->unsigned();
             $table->timestamps();
             $table->foreign('application_type_id')->references('id')->on('application_type')->onDelete('cascade');
         });
         Schema::create('area_translations', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('area_id')->unsigned();
             $table->string('locale')->index();
             $table->string('name');
             $table->unique(['area_id','locale']);
             $table->foreign('area_id')->references('id')->on('area')->onDelete('cascade');
         });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
       Schema::dropIfExists('area');
       Schema::dropIfExists('area_translations');
     }
}
