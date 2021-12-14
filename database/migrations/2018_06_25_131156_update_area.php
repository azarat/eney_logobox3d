<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('area', function (Blueprint $table) {
            $table->string('code', 4);
            $table->decimal('prepare_price', 15, 4)->default(0);
            $table->decimal('print_price', 15, 4)->default(0);
            $table->decimal('sticking_price', 15, 4)->default(0);
            $table->decimal('roasting_price', 15, 4)->default(0);
            $table->float('kx')->nullable();
            $table->float('kz')->nullable();
            $table->integer('max_colors')->nullable();
            $table->integer('max_copy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('area', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('prepare_price');
            $table->dropColumn('print_price');
            $table->dropColumn('sticking_price');
            $table->dropColumn('roasting_price');
            $table->dropColumn('kx');
            $table->dropColumn('kz');
            $table->dropColumn('max_colors');
            $table->dropColumn('max_copy');
        });
    }
}
