<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrintingsDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printings_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('session_id');
            $table->unsignedInteger('cart_product_id')->nullable();
            $table->string('file_url')->nullable();
            $table->string('remote_file_url')->nullable();
            $table->string('is_file_link')->nullable();
            $table->text('comment')->nullable();

            $table->foreign('product_id')->references('id')->on('product');
            $table->foreign('session_id')->references('id')->on('sessions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
