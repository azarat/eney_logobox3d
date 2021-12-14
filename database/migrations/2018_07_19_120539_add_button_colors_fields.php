<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddButtonColorsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->string('main_button_color')->nullable();
            $table->string('main_button_color_hover')->nullable();
            $table->string('main_button_color_active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn('main_button_color');
            $table->dropColumn('main_button_color_hover');
            $table->dropColumn('main_button_color_active');
        });
    }
}
