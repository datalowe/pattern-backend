<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('station', function (Blueprint $table) {
            $table->foreign(['city_id'], 'station_ibfk_1')->references(['id'])->on('city')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('station', function (Blueprint $table) {
            $table->dropForeign('station_ibfk_1');
        });
    }
}
