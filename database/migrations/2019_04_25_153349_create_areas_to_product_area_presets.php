<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasToProductAreaPresets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_product_area_preset', function (Blueprint $table) {
            $table->unsignedInteger('area_id');
            $table->unsignedInteger('product_area_preset_id');

            $table->foreign('area_id')
                ->references('id')
                ->on('areas')
                ->onDelete('RESTRICT');
            $table->foreign('product_area_preset_id')
                ->references('id')
                ->on('product_area_presets')
                ->onDelete('RESTRICT');
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
