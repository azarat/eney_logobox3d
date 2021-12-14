<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IFrameColors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->string('panel_text_color')->nullable();
            $table->string('add_button_color')->nullable();
            $table->string('bg_color')->nullable();
            $table->string('label_text_color')->nullable();
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
            $table->dropColumn(['panel_text_color', 'add_button_color', 'bg_color', 'label_text_color']);
        });
    }
}
