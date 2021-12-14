<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductToArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('product_to_area', function (Blueprint $table) {
           $table->integer('product_id')->unsigned();
           $table->integer('area_id')->unsigned();
           $table->unique(['product_id','area_id']);
           $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
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
       Schema::dropIfExists('product_to_area');
     }
}
