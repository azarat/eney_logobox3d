<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAreaAndApplicationType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('printings', function (Blueprint $table) {
            $table->unsignedInteger('area_id');
            $table->unsignedInteger('application_type_id');

            $table->foreign('area_id')->references('id')->on('area');
            $table->foreign('application_type_id')->references('id')->on('application_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('printings', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropForeign(['application_type_id']);

            $table->dropColumn('area_id');
            $table->dropColumn('application_type_id');
        });
    }
}
