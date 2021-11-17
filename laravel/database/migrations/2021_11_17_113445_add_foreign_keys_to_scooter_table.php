<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToScooterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scooter', function (Blueprint $table) {
            $table->foreign(['customer_id'], 'scooter_ibfk_1')->references(['id'])->on('customer')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['city_id'], 'scooter_ibfk_2')->references(['id'])->on('city')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['station_id'], 'scooter_ibfk_3')->references(['id'])->on('station')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scooter', function (Blueprint $table) {
            $table->dropForeign('scooter_ibfk_1');
            $table->dropForeign('scooter_ibfk_2');
            $table->dropForeign('scooter_ibfk_3');
        });
    }
}
