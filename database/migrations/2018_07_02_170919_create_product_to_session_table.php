<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductToSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_to_sessions', function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('session_id');

            $table->foreign('product_id')
                ->references('id')
                ->on('product')
                ->onDelete('cascade');
            $table->foreign('session_id')
                ->references('id')
                ->on('sessions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_to_sessions', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->dropForeign(['product_id']);
        });

        Schema::dropIfExists('products_to_sessions');
    }
}
